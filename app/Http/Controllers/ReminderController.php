<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Reminder;
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
        $types = [
            Reminder::TYPE_EMAIL => trans('reminder.type.email'),
            Reminder::TYPE_SMSAPI => trans('reminder.type.sms'),
        ];

        return View::make('reminder.index', [
            'reminders' => Reminder::logged()->orderBy('remind_at', 'asc')->get(),
            'title' => trans('reminder.index.title'),
            'types' => $types,
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
        $reminder = Reminder::find($id);

        if ($reminder == null || $reminder->user->id != Auth::id()) {
            return Redirect::route('reminders')->with('message', trans('reminder.not-found'))
                ->with('message_type', 'danger');
        }

        $reminder->delete();

        return Redirect::route('reminders')->with('message', trans('reminder.removed'));
    }
}
