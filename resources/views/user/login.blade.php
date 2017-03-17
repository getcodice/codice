@extends('app-simple')

@section('content')
@if (Session::has('message'))
    <div class="alert alert-danger alert-fixed alert-login">
        <p>{{ Session::get('message') }}</p>
    </div>
@endif

{!! BootForm::open()->action(route('user.login'))->class('form-login') !!}
    <h2 class="form-login-heading">@lang('user.login.title')</h2>
    {!! BootForm::email(trans('user.login.email'), 'email')->placeholder(trans('user.login.email'))->required()->autofocus()->hideLabel() !!}
    {!! BootForm::password(trans('user.login.password'), 'password')->placeholder(trans('user.login.password'))->required()->hideLabel() !!}
    {!! BootForm::checkbox(trans('user.login.remember'), 'remember') !!}
    {!! BootForm::submit(trans('user.login.submit'), 'btn-lg btn-block') !!}
    <a href="{!! route('password.email') !!}">@lang('passwords.forgotten')</a>
{!! BootForm::close() !!}
@stop