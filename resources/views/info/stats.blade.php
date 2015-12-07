@extends('app')

@section('content')
<h2 class="page-heading">@lang('info.stats.title')</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>@lang('info.stats.type')</th>
            <th>@lang('info.stats.amount')</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($stats as $name => $props)
        <tr class="{{ $props['class'] or '' }}">
            <td>{{ trans('info.stats.' . $name) }}
            <td>{{ $props['query'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
