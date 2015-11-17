<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\User;
use Input;
use Redirect;
use Session;
use Validator;
use View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout']]);
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return View::make('user.login');
    }

    /**
     * Processes a login form.
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
                if (Session::has('url.intended')) {
                    return Redirect::to(Session::get('url.intended'));
                }

                return Redirect::route('index');
            }

            return Redirect::back()->with('message', trans('user.login.invalid'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Logs user out
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();
        return Redirect::route('user.login');
    }
}
