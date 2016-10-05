<?php

namespace Codice\Http\Controllers;

use DB;
use Codice\Reminder;
use Codice\Reminders\ReminderService;
use Redirect;
use View;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reminders.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $tablePrefix = DB::getTablePrefix();

        return View::make('reminder.index', [
            'reminders' => Reminder::select(["{$tablePrefix}reminders.id", 'note_id', 'remind_at', 'type'])
                ->mine()
                ->orderBy('remind_at', 'asc')
                ->get(),
            'title' => trans('reminder.index.title'),
        ]);
    }

    /**
     * Cancel a reminder.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRemove($id)
    {
        $reminder = Reminder::findMine($id);

        ReminderService::get($reminder->type)->cancel($id);

        return Redirect::route('reminders')->with('message', trans('reminder.cancelled'));
    }
}
