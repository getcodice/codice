@extends('app')

@section('content')
<h2>Codice <small>{{ $version }}</small></h2>

<p class="lead text-justify"><strong>Codice</strong> @lang('info.about.about-codice')</p>

<div class="row">
    <div class="col-md-8">
        <textarea class="form-control" rows="7" style="resize:none" readonly>{{ $changelog }}</textarea>
    </div>
    <div class="col-md-4">
        <a href="{!! route('about.updates') !!}" class="btn btn-success btn-block">
            @lang('info.updates.check')
        </a>
    </div>
</div>
@stop
