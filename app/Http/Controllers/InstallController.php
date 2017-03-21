<?php

namespace Codice\Http\Controllers;

use App;
use Auth;
use Artisan;
use Codice\User;
use Exception;
use Illuminate\Http\Request;
use Lang;
use Redirect;
use Session;
use View;

class InstallController extends Controller
{
    private $denyInstallation = false;

    public function __construct()
    {
        if (file_exists(base_path('.env')) && !file_exists(storage_path('app/.install-pending'))) {
            $this->denyInstallation = true;
            return Redirect::route('index')->send();
        }

        if (Session::has('install-lang')) {
            App::setLocale(Session::get('install-lang'));
        }

        return true;
    }

    /**
     * Displays welcome message.
     *
     * GET /install
     */
    public function getWelcome()
    {
        if ($this->denyInstallation) {
            die;
        }

        touch(storage_path('app/.install-pending'));

        $languages = config('app.languages');
        unset($languages[Lang::getLocale()]);

        $languagesDisplay = [];
        foreach ($languages as $code => $name) {
            $languagesDisplay[] = '<a href="' . route('install.language', ['lang' => $code]) . '">' . $name . '</a>';
        }

        $languages = implode(', ', $languagesDisplay);

        return View::make('install.welcome', [
            'languages' => $languages,
            'progress' => 10,
            'step' => 1,
            'title' => trans('install.welcome.title'),
        ]);
    }

    /**
     * Checks for software requirements.
     *
     * GET /install/requirements (as install.requirements)
     */
    public function getRequirements()
    {
        $requiredExtensions = [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
        ];

        $writableDirectories = [
            'bootstrap/cache/',
            'storage/app/',
            'storage/framework/',
            'storage/logs/',
        ];

        $requirements = $permissions = [];
        $requirementsOk = $permissionsOk = true;

        foreach ($requiredExtensions as $extension) {
            $status = extension_loaded($extension);
            $requirements[$extension] = $status;

            if (!$status) {
                $requirementsOk = false;
            }
        }

        foreach ($writableDirectories as $directory) {
            $status = is_writable(base_path($directory));
            $permissions[$directory] = $status;

            if (!$status) {
                $permissionsOk = false;
            }
        }

        return View::make('install.requirements', [
            'permissions' => $permissions,
            'permissionsOk' => $permissionsOk,
            'progress' => 30,
            'requirements' => $requirements,
            'requirementsOk' => $requirementsOk,
            'step' => 2,
            'title' => trans('install.requirements.title'),
        ]);
    }

    /**
     * Displays form for creating .env file.
     *
     * GET /install/env (as install.environment)
     */
    public function getEnvironment()
    {
        $timezones = \DateTimeZone::listIdentifiers();

        return View::make('install.environment', [
            'progress' => 50,
            'step' => 3,
            'timezones' => array_combine($timezones, $timezones),
            'title' => trans('install.environment.title'),
        ]);
    }

    /**
     * Creates .env file.
     *
     * POST /install/env
     */
    public function postEnvironment(Request $request)
    {
        $this->validate($request, [
            'db_host' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
            'timezone' => 'required',
        ]);

        $env = "APP_ENV=production\nAPP_DEBUG=false\n";
        $env .= 'APP_KEY=' . str_random(32) . "\n";
        $env .= 'APP_TIMEZONE=' . $request->input('timezone') . "\n\n";
        $env .= 'DB_HOST=' . $request->input('db_host') . "\n";
        $env .= 'DB_DATABASE=' . $request->input('db_name') . "\n";
        $env .= 'DB_USERNAME=' . $request->input('db_user') . "\n";
        $env .= 'DB_PASSWORD=' . $request->input('db_password') . "\n";
        $env .= 'DB_PREFIX=' . $request->input('db_prefix') . "\n\n";
        $env .= "CACHE_DRIVER=file\nSESSION_DRIVER=file\nQUEUE_DRIVER=sync\n\nMAIL_DRIVER=mail";

        file_put_contents(base_path('.env'), $env);

        // Watch out!
        // We have just filled in .env file...
        // what is going to change app key for the next request...
        // what is then going to make it impossible to decrypt our session...
        // what is then going to reset installer language
        // We are sneaky bastards so we'll pass it in URL and set it again in next step!
        return Redirect::route('install.database', ['lang' => Lang::getLocale()]);
    }

    /**
     * Fills in a database.
     *
     * GET /install/database/{lang} (as install.database)
     */
    public function getDatabase($lang)
    {
        // We are reading installer language from the URL and setting it again
        // @see: postEnvironment()
        App::setLocale($lang);
        Session::put('install-lang', $lang);

        $error = null;

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return View::make('install.database', [
            'error' => $error,
            'progress' => 70,
            'step' => 4,
            'title' => trans('install.database.title'),
        ]);
    }

    /**
     * Displays form for creating first user.
     *
     * GET /install/user (as install.user)
     */
    public function getUser()
    {
        return View::make('install.user', [
            'progress' => 90,
            'step' => 5,
            'title' => trans('install.user.title'),
        ]);
    }

    /**
     * Creates first user.
     *
     * POST /install/user
     */
    public function postUser(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $options = User::$defaultOptions;
        $options['language'] = Lang::getLocale();

        $user = new User;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->options = $options;
        $user->save();

        $user->addWelcomeNote();

        Auth::loginUsingId(1);

        $unlink = @unlink(storage_path('app/.install-pending'));

        $message = $unlink ? 'success' : 'do-unlink';

        return Redirect::route('index')->with('message_type', 'success')
            ->with('message', trans("install.final.$message"));
    }

    /**
     * Changes installer's language.
     *
     * GET /install/change-language/{lang} (as install.language)
     */
    public function getChangeLanguage($lang)
    {
        Session::put('install-lang', $lang);

        return Redirect::action('InstallController@getWelcome');
    }
}
