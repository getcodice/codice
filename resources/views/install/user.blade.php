@extends('install.template')

@section('content')
<h2>@lang('install.user.title')</h2>
<p>@lang('install.user.content')</p>

{!! BootForm::open()->action(route('install.user')) !!}
    {!! BootForm::text(trans('install.user.name'), 'name') !!}
    {!! BootForm::email(trans('install.user.email'), 'email') !!}
    {!! BootForm::password(trans('install.user.password'), 'password') !!}
    {!! BootForm::password(trans('install.user.password-confirmation'), 'password_confirmation') !!}

    {!! BootForm::submit(trans('install.btn-next'), 'btn-success') !!}
{!! BootForm::close() !!}
@stop