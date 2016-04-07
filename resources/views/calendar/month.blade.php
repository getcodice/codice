@extends('app')

@section('content')
<h2 class="page-heading primary">
    @lang('calendar.title')
    <span class="page-heading-aside">
        <a href="{!! $month_previous !!}">@icon('arrow-left') <span class="sr-only">@lang('calendar.month.previous')</span></a>
        @lang("calendar.months.$month") {{ $year }}
        <a href="{!! $month_next !!}"><span class="sr-only">@lang('calendar.month.next')</span> @icon('arrow-right')</a>
    </span>
</h2>

<table class="calendar">
    <tr>
    @for($day = 0; $day < 7; $day++)
            <th>@lang("calendar.days.$day")</th>
    @endfor
    </tr>
    @foreach ($weeks as $week)
    <tr>
        @foreach ($week as $day)
            <td class="{{ calendar_class($day, $month, $events) }}">
                <div class="calendar-day {{ $day->isToday() ? 'today' : '' }}">
                    <a href="{{ route('calendar.day', [
                        'year' => $day->year,
                        'month' => $day->format('m'),
                        'day' => $day->format('d')
                    ]) }}">{{ $day->format('j')  }}</a>
                </div>
            </td>
        @endforeach
    </tr>
    @endforeach
</table>
@stop
