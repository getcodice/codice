@extends('app')

@section('content')
    <h2 class="page-heading info">
        @lang('labels.index.title')
        <a href="{!! route('label.create') !!}" class="page-heading-link">@lang('labels.index.create')</a>
    </h2>

    <div class="codice-container">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="col-md-5">@lang('labels.index.name')</th>
                <th class="col-md-3">@lang('labels.index.count')</th>
                <th class="col-md-4">@lang('labels.index.controls')</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($labels as $label)
            <tr>
                <td>
                    <a href="{!! route('label', ['id' => $label->id]) !!}" class="label label-{{ $colors[$label->color] }}">{{ $label->name }}</a>
                </td>
                <td>
                    {{ $label->count }}
                </td>
                <td>
                    <a href="{!! route('label.edit', ['id' => $label->id]) !!}" class="action">@icon('pencil') @lang('labels.index.edit')</a>
                    <a href="{!! route('label.remove', ['id' => $label->id]) !!}" class="action" data-confirm="delete">@icon('trash-o') @lang('labels.index.remove')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@stop
