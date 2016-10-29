<?php

namespace Codice\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Codice\Note;
use Codice\Support\Calendar;
use Lang;
use View;

class CalendarController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        return $this->displayMonth(date('m'), date('Y'));
    }

    public function getMonth($year, $month)
    {
        return $this->displayMonth($month, $year);
    }

    public function getDay($year, $month, $day)
    {
        $perPage = Auth::user()->options['notes_per_page'];

        $notes = Note::with('labels')->mine()->whereDate('created_at', '=', "{$year}-{$month}-{$day}")
            ->orWhere(function ($query) use ($year, $month, $day) {
                $query->whereDate('expires_at', '=', "{$year}-{$month}-{$day}");
            })
            ->latest()
            ->simplePaginate($perPage);

        $quickform = quickform([
            'expires_at' => Carbon::createFromDate($year, $month, $day)->format(trans('app.date')),
            'target_url' => route('calendar.day', ['year' => $year, 'month' => $month, 'day' => $day]),
        ]);

        $title = trans('calendar.day-title', [
            'day' => $day,
            'month' => trans(Lang::has('calendar.months-genitive', null, false) ?
                           "calendar.months-genitive.$month" :
                           "calendar.months.$month"),
            'year' => $year,
        ]);

        return View::make('calendar.day', [
            'notes' => $notes,
            'quickform' => $quickform,
            'title' => $title,
        ]);
    }

    private function displayMonth($month, $year)
    {
        $calendar = new Calendar($month, $year);

        $eventsCreated = Note::mine()->where(function ($query) use ($month, $year) {
            $query->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year);
        })->get();
        $eventsExpiring = Note::mine()->where(function ($query) use ($month, $year) {
            $query->whereMonth('expires_at', '=', $month)->whereYear('expires_at', '=', $year);
        })->get();

        // Rewrite to the form we need
        $events = [];
        foreach ($eventsCreated as $event) {
            $events[$event->created_at->month . '-' . $event->created_at->day]['created'] = true;
        }
        foreach ($eventsExpiring as $event) {
            $events[$event->expires_at->month . '-' . $event->expires_at->day]['expiring'] = true;
        }

        $nextMonth = Carbon::create($year, $month)->addMonth();
        $previousMonth = Carbon::create($year, $month)->subMonth();

        return View::make('calendar.month', [
            'events' => $events,
            'month' => $month,
            'month_next' => route('calendar.month', ['year' => $nextMonth->year, 'month' => pad_zero($nextMonth->month)]),
            'month_previous' => route('calendar.month', ['year' => $previousMonth->year, 'month' => pad_zero($previousMonth->month)]),
            'title' => trans('calendar.title'),
            'weeks' => $calendar->createMonthArray(),
            'year' => $year,
        ]);
    }
}
