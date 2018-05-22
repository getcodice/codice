<?php

namespace Codice\Http\Controllers;

use DB;
use Codice\Reminder;
use Codice\Reminders\ReminderService;
use Redirect;
use View;

class ReminderController extends Controller
{
    /**
     * Displays listing of reminders.
     *
     * GET /reminders (as reminders)
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
     * Cancels a reminder.
     *
     * GET /reminder/{id}/remove (as reminder.remove)
     */
    public function getRemove($id)
    {
        $reminder = Reminder::findMine($id);

        ReminderService::get($reminder->type)->cancel($id);

        return Redirect::route('reminders')->with('message', trans('reminder.cancelled'));
    }
}
