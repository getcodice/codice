@extends('install.template')

@section('content')
<p>@lang('install.requirements.content')</p>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class="col-sm-7">@lang('install.requirements.software')</th>
        <th class="col-sm-5">@lang('install.requirements.status')</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($software as $name => $status)
        <tr>
            <td>{{ $name }}</td>
            <td>{{ $status ? trans('install.requirements.available') : trans('install.requirements.unavailable')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-sm-7">@lang('install.requirements.extension')</th>
            <th class="col-sm-5">@lang('install.requirements.status')</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($requirements as $name => $status)
        <tr>
            <td>{{ $name }}</td>
            <td>{{ $status ? trans('install.requirements.available') : trans('install.requirements.unavailable')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-sm-7">@lang('install.requirements.directory')</th>
            <th class="col-sm-5">@lang('install.requirements.status')</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($permissions as $name => $status)
        <tr>
            <td>{{ $name }}</td>
            <td>{!! $status ? trans('install.requirements.status-dir-ok') : trans('install.requirements.status-dir-error') !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if (!$softwareOk)
    <div class="alert alert-danger">@lang('install.requirements.error-software')</div>
@endif

@if (!$requirementsOk)
<div class="alert alert-danger">@lang('install.requirements.error-extensions')</div>
@endif

@if (!$permissionsOk)
<div class="alert alert-danger">@lang('install.requirements.error-directories')</div>
@endif

@if ($softwareOk && $requirementsOk && $permissionsOk)
<a href="{!! route('install.environment') !!}" class="btn btn-success">@lang('install.btn-next')</a>
@endif
@stop
