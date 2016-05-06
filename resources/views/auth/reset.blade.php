@extends('app-simple')

@section('content')
{!! BootForm::open()->action(action('UserController@postReset')) !!}
    {!! BootForm::email(trans('passwords.form.email'), 'email') !!}
    {!! BootForm::password(trans('passwords.form.password'), 'password') !!}
    {!! BootForm::password(trans('passwords.form.password_confirmation'), 'password_confirmation') !!}
    {!! BootForm::submit(trans('passwords.form.submit-reset')) !!}
    <input type="hidden" name="token" value="{{ $token  }}">
{!! BootForm::close() !!}
@stop
