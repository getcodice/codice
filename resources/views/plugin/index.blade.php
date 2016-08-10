@extends('app')

@section('content')
<h2 class="page-heading">@lang('plugin.index.title')</h2>

@if (count($plugins))
    @foreach ($plugins as $id => $plugin)
        <div class="plugin plugin-{{ $plugin['state'] }}">
            <h3 class="plugin-header">
                @if ($plugin['state'] != 'enabled')
                <span class="shield plugin-state">
                    <span class="shield-only label-warning">@lang('plugin.index.' . $plugin['state'])</span>
                </span>
                @endif
                {{ $plugin['details']['name'] }}
                <span class="plugin-version">{{ $plugin['details']['version'] }}</span>
                <span class="shield shield-divided">
                    <span class="shield-left label-default">@lang('plugin.index.author')</span>
                    <span class="shield-right label-light">{{ $plugin['details']['author'] }}</span>
                </span>
            </h3>
            <div class="plugin-meta">
                <p class="plugin-description">{{ $plugin['details']['description'] }}</p>
                <p class="plugin-shields">
                </p>
            </div>
            <div class="plugin-controls">
                @if ($plugin['state'] == 'not-installed')
                    <a class="plugin-control plugin-control-enable" href="{!! route('plugin.install', ['id' => $id]) !!}">
                        @lang('plugin.index.install')
                    </a>
                @else
                    @if ($plugin['state'] == 'enabled')
                        <a class="plugin-control plugin-control-disable" href="{!! route('plugin.disable', ['id' => $id]) !!}">
                            @lang('plugin.index.disable')
                        </a>
                    @else
                        <a class="plugin-control plugin-control-enable" href="{!! route('plugin.enable', ['id' => $id]) !!}">
                            @lang('plugin.index.enable')
                        </a>
                    @endif
                    <a class="plugin-control plugin-control-disable" href="{!! route('plugin.uninstall', ['id' => $id]) !!}">
                        @lang('plugin.index.uninstall')
                    </a>
                @endif
            </div>
        </div>
    @endforeach
@else
<h1 class="app-error">@lang('plugin.none.title')</h1>
<h2 class="app-error">@lang('plugin.none.content')</h2>
@endif
@stop
