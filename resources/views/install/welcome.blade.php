@extends('install.template')

@section('content')
<p>@lang('install.welcome.para1')</p>
<p>@lang('install.welcome.para2')</p>
<p>Installer is also available in following languages (click to switch): {!! $languages  !!}</p>

<a href="{!! route('install.requirements') !!}" class="btn btn-success">
    @lang('install.btn-next')
</a>
@stop
