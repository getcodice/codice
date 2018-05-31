@extends('app')

@section('content')
<h2 class="page-heading danger">Codice <small>{{ $version }}</small></h2>

<div class="codice-container">
<p class="lead text-justify"><strong>Codice</strong> @lang('info.about.about-codice')</p>

<div class="row">
    <div class="col-md-8 about-changelog">
        <p><strong>@lang('info.about.changelog-title')</strong></p>
        {!! $changelog !!}
    </div>
    <div class="col-md-4">
        <a href="{!! config('services.website.url') !!}" class="btn btn-default btn-block">
            @lang('info.about.link.main')
        </a>
        <a href="{!! config('services.website.documentation') !!}" class="btn btn-default btn-block">
            @lang('info.about.link.documentation')
        </a>
        <a href="{!! route('about.updates') !!}" class="btn btn-success btn-block">
            @lang('info.updates.check')
        </a>
    </div>
</div>
</div>
@stop
