<?php

namespace Codice\Http\Controllers;

use App;
use Artisan;
use Codice\User;
use Exception;
use Input;
use Lang;
use Redirect;
use Session;
use Validator;
use View;

class InstallController extends Controller
{
    private $denyInstallation = false;

    /**
     * InstallController constructor.
     */
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
     * Display welcome message.
     *
     * @return \Illuminate\Http\Response
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
     * Check for software requirements.
     *
     * @return \Illuminate\Http\Response
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
     * Display form for creating .env file.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEnvironment()
    {
        return View::make('install.environment', [
            'progress' => 50,
            'step' => 3,
            'title' => trans('install.environment.title'),
        ]);
    }

    /**
     * Create .env file.
     *
     * @return \Illuminate\Http\Response
     */
    public function postEnvironment()
    {
        $validator = Validator::make(Input::all(), [
            'db_host' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $env = "APP_ENV=production\nAPP_DEBUG=false\n";
        $env .= 'APP_KEY=' . str_random(32) . "\n\n";
        $env .= 'DB_HOST=' . Input::get('db_host') . "\n";
        $env .= 'DB_DATABASE=' . Input::get('db_name') . "\n";
        $env .= 'DB_USERNAME=' . Input::get('db_user') . "\n";
        $env .= 'DB_PASSWORD=' . Input::get('db_password') . "\n\n";
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
     * Fill a database with content.
     *
     * @param  string $lang Currently used installer language
     * @return \Illuminate\Http\Response
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
     * Display form for creating first user.
     *
     * @return \Illuminate\Http\Response
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
     * Create first user.
     *
     * @return \Illuminate\Http\Response
     */
    public function postUser()
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $options = User::$defaultOptions;
        $options['language'] = Lang::getLocale();

        $user = new User;
        $user->name = Input::get('name');
        $user->email = Input::get('email');
        $user->password = bcrypt(Input::get('password'));
        $user->options = $options;
        $user->save();

        $user->addWelcomeNote();

        return Redirect::route('install.final');
    }

    /**
     * Display final screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFinal()
    {
        $unlink = @unlink(storage_path('app/.install-pending'));

        return View::make('install.final', [
            'progress' => 100,
            'step' => 6,
            'title' => trans('install.final.title'),
            'unlink' => $unlink,
        ]);
    }

    public function getChangeLanguage($lang)
    {
        Session::put('install-lang', $lang);

        return Redirect::action('InstallController@getWelcome');
    }
}
