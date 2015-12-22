@extends('app')

@section('content')
<h2 class="page-heading">@lang('plugin.index.title')</h2>

@if (count($plugins))
<table class="table table-bordered">
    <thead>
        <tr>
            <th>@lang('plugin.index.plugin')</th>
            <th>@lang('plugin.index.state')</th>
            <th>@lang('plugin.index.controls')</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($plugins as $id => $plugin)
        <tr>
            <td>
                <b>{{ $plugin['details']['name'] }} ({{ $plugin['details']['version'] }})</b>
                <p>{{ $plugin['details']['description'] }}</p>
                <p class="small">@lang('plugin.index.author') {{ $plugin['details']['author'] }}</p>
            </td>
            <td>
                @if ($plugin['installed'])
                    @if ($plugin['enabled'])
                        @lang('plugin.index.enabled')
                    @else
                        @lang('plugin.index.disabled')
                    @endif
                @else
                    @lang('plugin.index.not-installed')
                @endif
            </td>
            <td>
                @if ($plugin['installed'])
                    @if ($plugin['enabled'])
                        <a href="{!! route('plugin.disable', ['id' => $id]) !!}">
                            @lang('plugin.index.disable')
                        </a>
                    @else
                        <a href="{!! route('plugin.enable', ['id' => $id]) !!}">
                            @lang('plugin.index.enable')
                        </a>
                    @endif
                    <br><a href="{!! route('plugin.uninstall', ['id' => $id]) !!}" data-confirm="uninstall">
                        @lang('plugin.index.uninstall')
                    </a>
                @else
                    <a href="{!! route('plugin.install', ['id' => $id]) !!}">
                        @lang('plugin.index.install')
                    </a><br>
                    <a href="{!! route('plugin.remove', ['id' => $id]) !!}" data-confirm="delete">
                        @lang('plugin.index.remove')
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<h1 class="app-error">@lang('plugin.none.title')</h1>
<h2 class="app-error">@lang('plugin.none.content')</h2>
@endif
@stop
