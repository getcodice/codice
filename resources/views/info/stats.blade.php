@extends('app')

@section('content')
<h2 class="page-heading danger">@lang('info.stats.title')</h2>

<div class="codice-container">
<table class="table table-hover">
    <thead>
        <tr>
            <th>@lang('info.stats.type')</th>
            <th>@lang('info.stats.amount')</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($stats as $type => $amount)
        <tr>
            <td>{{ trans('info.stats.' . $type) }}</td>
            <td>{!! $amount !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@stop
