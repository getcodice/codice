@extends('app-simple')

@section('content')
@if (Session::has('status'))
    <div class="alert alert-success alert-fixed alert-login">
        <p>{{ Session::get('status') }}</p>
    </div>
@endif

{!! BootForm::open()->action(route('password.email')) !!}
    {!! BootForm::email(trans('passwords.form.email'), 'email') !!}
    {!! BootForm::submit(trans('passwords.form.submit')) !!}
    <a href="{!! route('user.login') !!}" class="btn btn-default">@lang('passwords.form.cancel')</a>
{!! BootForm::close() !!}
@stop
