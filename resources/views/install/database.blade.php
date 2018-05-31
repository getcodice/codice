@extends('install.template')

@section('content')
@if ($error)
    <div class="alert alert-danger">
        <p>@lang('install.database.error')</p>
        <pre>{{ $error }}</pre>
        <p>@lang('install.database.check-config')</p>
        <p>
            @lang('install.database.report')
            <a href="{!! config('services.website.issues') !!}" class="alert-link">
                @lang('install.database.report-link')
            </a>.
        </p>
    </div>

    <a href="{!! route('install.environment') !!}" class="btn btn-default">@lang('install.btn-prev')</a>
@else
    <p>@lang('install.database.success')</p>

    <a href="{!! route('install.user') !!}" class="btn btn-success">@lang('install.btn-next')</a>
@endif
@stop
