@extends('app')

@section('content')
    <h2>@lang('reminder.index.title')</h2>

    <table class="table">
        <thead>
            <tr>
                <th class="col-md-3">@lang('reminder.index.note-id')</th>
                <th class="col-md-4">@lang('reminder.index.remind-at')</th>
                <th class="col-md-3">@lang('reminder.index.type')</th>
                <th class="col-md-2">@lang('reminder.index.controls')</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($reminders as $reminder)
            <tr>
                <td>
                    <a href="">{{ trans('reminder.index.note-link', ['id' => $reminder->note_id]) }}</a>
                </td>
                <td>
                    {{ $reminder->remind_at->format('d.m.Y H:i') }}
                    ({{ $reminder->remind_at->diffForHumans() }})
                </td>
                <td>
                    {{ $types[$reminder->type] }}
                </td>
                <td>
                    <a href="{!! route('reminder.remove', ['id' => $reminder->id]) !!}" class="action confirm-deletion">@icon('trash-o') @lang('reminder.index.remove')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
