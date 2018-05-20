@extends('install.template')

@section('content')
<p>@lang('install.welcome.para1')</p>
<p>@lang('install.welcome.para2')</p>
<p>You may also switch the language to any of following: {!! $languages  !!}</p>

<a href="{!! route('install.requirements') !!}" class="btn btn-success">
    @lang('install.btn-next')
</a>
@stop
