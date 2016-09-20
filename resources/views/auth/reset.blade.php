@extends('app-simple')

@section('content')
<h2 class="page-heading info">@lang('passwords.form.title')</h2>

<div class="codice-container">
{!! BootForm::open()->action(action('UserController@postReset')) !!}
    {!! BootForm::email(trans('passwords.form.email'), 'email') !!}
    {!! BootForm::password(trans('passwords.form.password'), 'password') !!}
    {!! BootForm::password(trans('passwords.form.password_confirmation'), 'password_confirmation') !!}
    {!! BootForm::submit(trans('passwords.form.submit-reset'), 'btn-primary') !!}
    <input type="hidden" name="token" value="{{ $token  }}">
{!! BootForm::close() !!}
</div>
@stop
