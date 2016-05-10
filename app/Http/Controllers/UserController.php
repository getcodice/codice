<?php

namespace Codice\Http\Controllers;

use App;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Input;
use Redirect;
use Validator;
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
     * @return \Illuminate\Http\Response
     */
    public function postLogin()
    {
        $credentials = [
            'email' => Input::get('email'),
            'password' => Input::get('password')
        ];

        $validator = Validator::make($credentials, [
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt($credentials)) {
                return Redirect::intended(route('index'));
            }

            return Redirect::back()->with('message', trans('user.login.invalid'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
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
            'currentLanguage' => Auth::user()['options']['language'],
            'languages' => config('app.languages'),
            'title' => trans('user.settings.title'),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Process settings form.
     *
     * @return \Illuminate\Http\Response
     */
    public function postSettings()
    {
        $allowedLanguages = implode(',', array_keys(config('app.languages')));

        $validator = Validator::make(Input::all(), [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            // FIXME: regex would be fine, but what about other i18n support?
            //'options.phone' => 'numeric',
            'password_new' => 'confirmed',
            'options.language' => "required|in:$allowedLanguages",
            'options.notes_per_page' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            // Set app's locale so correct message is displayed when language has
            // been just changed.
            App::setLocale(Input::get('options')['language']);

            $message = trans('user.settings.success');

            $user = Auth::user();
            if (Input::has('password') && Input::has('password_new')) {
                if (!Hash::check(Input::get('password'), $user->password)) {
                    return Redirect::back()->with('message', trans('user.settings.password-wrong'))
                        ->with('message_type', 'danger');
                }

                $user->password = bcrypt(Input::get('password_new'));
                $message = trans('user.settings.success-password');
            }
            $user->email = Input::get('email');
            $user->options = Input::get('options');
            $user->save();

            event('user.save', [$user]);

            return Redirect::back()->with('message', $message);
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * (Re-)insert welcome note on demand.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInsertWelcomeNote()
    {
        Auth::user()->addWelcomeNote(false);

        return Redirect::route('index');
    }

    /**
     * @inheritdoc
     */
    protected function getEmailSubject()
    {
        return trans('passwords.email.subject');
    }
}
