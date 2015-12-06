@extends('app')

@section('content')
<h2>@lang('user.settings.title')</h2>

{!! BootForm::open()->action(route('settings')) !!}
<div class="panel panel-default">
    <div class="panel-heading">@lang('user.settings.panel-account')</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                {!!
                    BootForm::text(trans('user.settings.login'), 'login')
                        ->disabled()
                        ->value($user->name)
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    BootForm::email(trans('user.settings.email'), 'email')
                        ->value($user->email)
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    BootForm::text(trans('user.settings.phone'), 'options[phone]')
                        ->value($user->options['phone'])
                !!}
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">@lang('user.settings.panel-password')</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::password(trans('user.settings.password'), 'password') !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::password(trans('user.settings.password-new'), 'password_new') !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::password(trans('user.settings.password-new-confirmation'), 'password_new_confirmation') !!}
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">@lang('user.settings.panel-application')</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                {!!
                    BootForm::select(trans('user.settings.language'), 'options[language]')
                        ->options($languages)
                        ->select($currentLanguage)
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    BootForm::text(trans('user.settings.notes_per_page'), 'options[notes_per_page]')
                        ->type('number')
                        ->value($user->options['notes_per_page'])
                !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    {!! BootForm::submit(trans('user.settings.submit'), 'btn-primary') !!}
</div>
{!! BootForm::close() !!}
@stop
