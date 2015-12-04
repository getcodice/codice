@extends('app')

@section('content')
<h2>Codice <small>{{ $version }}</small></h2>

<p class="lead"><strong>Codice</strong> @lang('info.about.about-codice')</p>

<div class="row">
    <textarea class="col-md-8" rows="7" disabled>{{ $changelog }}</textarea>
</div>
@stop
