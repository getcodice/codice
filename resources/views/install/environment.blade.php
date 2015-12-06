@extends('install.template')

@section('content')
<h2>@lang('install.environment.title')</h2>
<p>@lang('install.environment.content')</p>

{!! BootForm::open()->action(route('install.environment')) !!}
    <fieldset>
    <legend>@lang('install.environment.db-legend')</legend>
    {!!
        BootForm::text(trans('install.environment.db-host'), 'db_host')
            ->helpBlock(trans('install.environment.db-host-help'))
    !!}
    {!! BootForm::text(trans('install.environment.db-name'), 'db_name') !!}
    {!! BootForm::text(trans('install.environment.db-user'), 'db_user') !!}
    {!! BootForm::password(trans('install.environment.db-password'), 'db_password') !!}
    </fieldset>

    {!! BootForm::submit(trans('install.btn-next'), 'btn-success') !!}
{!! BootForm::close() !!}
@stop