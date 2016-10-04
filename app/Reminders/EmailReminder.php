<?php

namespace Codice\Reminders;

use App;
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

        // Send email in user's language
        App::setLocale($user->options['language']);

        Mail::send('emails.reminder', $data, function ($message) use ($user) {
            $message->from('reminders@codice.dev', 'Codice');
            $message->to($user->email);
            $message->subject(trans('reminder.email.subject'));
        });

        // Reset back to default language
        App::setLocale(config('app.locale'));

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
