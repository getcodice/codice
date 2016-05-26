@extends('app')

@section('content')
<h2 class="page-heading danger">@lang('note.remove.title')</h2>

<div class="codice-container">
    <p>@lang('note.remove.confirm')</p>

    <a href="{!! route('note.remove', ['id' => $id]) !!}" class="btn btn-primary">@lang('note.remove.ok')</a>
    <a href="{!! url('/') !!}" class="btn btn-default">@lang('note.remove.cancel')</a>
</div>
@stop
