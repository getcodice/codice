<?php

namespace Codice\Http\Controllers;

use App;
use Auth;
use Codice\Plugins\Action;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Redirect;
use View;

class UserController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest', ['only' => 'getLogin']);
        $this->middleware('auth', ['except' => ['getLogin', 'postLogin', 'getEmail', 'postEmail', 'getReset', 'postReset']]);

        // Set redirectPath so that user is redirected correctly after the password reset
        $this->redirectTo = '/';
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return View::make('user.login', [
            'title' => trans('user.login.title'),
        ]);
    }

    /**
     * Process a login form.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required',
        ]);

        $isValid = Auth::attempt($credentials);

        /**
         * Executed on login attempt
         *
         * @since 0.4
         *
         * @param string $email E-mail address used to log in
         * @param bool $isValid Whether user logged in successfully
         * @param \Codice\User|null $user User object if credentials were correct, null otherwise
         */
        Action::call('user.login', [
            'email' => $credentials['email'],
            'isValid' => $isValid,
            'user' => $isValid ? Auth::user() : null,
        ]);

        if ($isValid) {
            return Redirect::intended(route('index'));
        }

        return Redirect::back()->with('message', trans('user.login.invalid'));
    }

    /**
     * Log user out
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();
        return Redirect::route('user.login');
    }

    /**
     * Display settings section for current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSettings()
    {
        return View::make('user.settings', [
            'languages' => config('app.languages'),
            'title' => trans('user.settings.title'),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Process settings form.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSettings(Request $request)
    {
        $allowedLanguages = implode(',', array_keys(config('app.languages')));

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password_new' => 'confirmed',
            'options.language' => "required|in:$allowedLanguages",
            'options.notes_per_page' => 'required|numeric',
        ]);

        // Set app's locale so correct message is displayed when language has
        // been just changed.
        App::setLocale($request->input('options')['language']);

        $message = trans('user.settings.success');

        $user = Auth::user();
        if ($request->has('password', 'password_new')) {
            if (!Hash::check($request->input('password'), $user->password)) {
                return Redirect::back()->with('message', trans('user.settings.password-wrong'))
                    ->with('message_type', 'danger');
            }

            $user->password = bcrypt($request->input('password_new'));
            $message = trans('user.settings.success-password');
        }
        $user->email = $request->input('email');
        $user->options = $request->input('options');
        $user->save();

        event('user.save', [$user]);

        return Redirect::back()->with('message', $message);
    }

    /**
     * @inheritdoc
     */
    protected function getEmailSubject()
    {
        return trans('passwords.email.subject');
    }
}
