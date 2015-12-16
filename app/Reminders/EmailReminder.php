<?php

namespace Codice\Reminders;

use Codice\Reminder;
use Mail;

class EmailReminder extends ReminderService
{
    /**
     * {@inheritdoc}
     */
    protected $id = 'email';

    /**
     * {@inheritdoc}
     */
    public function cron(Reminder $reminder)
    {
        $user = $reminder->user;

        $data = [
            'note' => $reminder->note,
            'user' => $user,
        ];

        Mail::send('emails.reminder', $data, function ($message) use ($user) {
            $message->from('reminders@codice.dev', 'Codice');
            $message->to($user->email);
            $message->subject(trans('reminder.email.subject'));
        });

        $reminder->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return trans("reminder.services.email");
    }
}
