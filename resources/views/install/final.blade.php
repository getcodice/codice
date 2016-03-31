@extends('install.template')

@section('content')
<p>@lang('install.final.content')</p>

@if (!$unlink)
<p>@lang('install.final.unlink-failed')</p>
@endif

<a href="{!! route('user.login') !!}" class="btn btn-success">
    @lang('install.final.login')
</a>
@stop
