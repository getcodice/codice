@extends('app')

@section('content')
<h2 class="page-heading">@lang('plugin.index.title')</h2>

@if (count($plugins))
    @foreach ($plugins as $id => $plugin)
        <div class="plugin plugin-{{ $plugin['enabled'] ? 'enabled' : 'disabled' }}">
            <h3 class="plugin-header">
                @if (!$plugin['enabled'])
                <span class="shield plugin-status">
                    <span class="shield-only label-warning">Disabled</span>
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
                @if ($plugin['enabled'])
                    <a class="plugin-control plugin-control-disable" href="{!! route('plugin.disable', ['id' => $id]) !!}">
                        @lang('plugin.index.disable')
                    </a>
                @else
                    <a class="plugin-control plugin-control-enable" href="{!! route('plugin.enable', ['id' => $id]) !!}">
                        @lang('plugin.index.enable')
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
