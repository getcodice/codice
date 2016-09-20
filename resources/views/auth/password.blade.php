@extends('app-simple')

@section('content')
@if (Session::has('status'))
    <div class="alert alert-success alert-fixed alert-login">
        <p>{{ Session::get('status') }}</p>
    </div>
@endif

<h2 class="page-heading info">
    @lang('passwords.form.title')
    <span class="page-heading-aside"><a href="{!! route('user.login') !!}">@lang('passwords.form.cancel')</a></span>
</h2>

<div class="codice-container">
{!! BootForm::open()->action(route('password.email')) !!}
    {!! BootForm::email(trans('passwords.form.email'), 'email') !!}
    {!! BootForm::submit(trans('passwords.form.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
</div>
@stop
