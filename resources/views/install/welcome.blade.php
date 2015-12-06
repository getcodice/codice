@extends('install.template')

@section('content')
<h2>@lang('install.welcome.title')!</h2>
<p>@lang('install.welcome.content')</p>

<a href="{!! route('install.requirements') !!}" class="btn btn-success">
    @lang('install.btn-next')
</a>
@stop