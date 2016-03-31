@extends('install.template')

@section('content')
@if ($error)
<div class="alert alert-danger">{{ $error }}</div>
@else
<p>@lang('install.database.success')</p>

<a href="{!! route('install.user') !!}" class="btn btn-success">@lang('install.btn-next')</a>
@endif
@stop
