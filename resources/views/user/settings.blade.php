@extends('app')

@section('content')
<h2 class="page-heading">@lang('user.settings.title')</h2>

{!! BootForm::open()->action(route('settings'))->class('settingsForm') !!}
<div class="panel panel-default">
    <div class="panel-heading">@lang('user.settings.panel-account')</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">@lang('user.settings.login')</label>
                    <p class="form-control-static">{{ $user->name }}</p>
                </div>
            </div>
            <div class="col-md-4">
                {!!
                    BootForm::email(trans('user.settings.email'), 'email')
                        ->value($user->email)
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
                        ->select($user->options['language']);
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

@section('footer')
<script>
    var $currentEmail = $("#email").val();
    var $settingsForm = $('form.settingsForm');

    $settingsForm.on('submit', function(e) {
        if ($("#email").val() != $currentEmail) {
            e.preventDefault();

            bootbox.confirm(codiceLang.confirmEmailChange, function(result) {
                if (result) {
                    $settingsForm[0].submit();
                } else {
                    $("#email").val($currentEmail);
                }
            });
        }
    })

</script>
@stop
