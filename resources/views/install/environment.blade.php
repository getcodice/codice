@extends('install.template')

@section('content')
<p>@lang('install.environment.content')</p>

{!! BootForm::open()->action(route('install.environment')) !!}
    <fieldset>
    <legend>@lang('install.environment.db')</legend>
    {!!
        BootForm::text(trans('install.environment.db-host'), 'db_host')
            ->helpBlock(trans('install.environment.db-host-help'))
    !!}
    {!! BootForm::text(trans('install.environment.db-name'), 'db_name') !!}
    {!! BootForm::text(trans('install.environment.db-user'), 'db_user') !!}
    {!! BootForm::password(trans('install.environment.db-password'), 'db_password') !!}
    {!!
        BootForm::text(trans('install.environment.db-prefix'), 'db_prefix', 'codice_')
            ->helpBlock(trans('install.environment.db-prefix-help'))
    !!}
    </fieldset>

    <fieldset>
    <legend>@lang('install.environment.other')</legend>
    {!! BootForm::select(trans('install.environment.timezone'), 'timezone', $timezones)->select('UTC') !!}
    </fieldset>

    {!! BootForm::submit(trans('install.btn-next'), 'btn-success') !!}
{!! BootForm::close() !!}
@stop
