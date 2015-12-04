<?php

namespace Codice\Console\Commands;

use Codice\Reminder;
use Illuminate\Console\Command;
use Mail;

class RemindersSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;

        // Get all reminders which were not handled yet
        $reminders = Reminder::whereRaw('remind_at < NOW()')
            ->where('type', Reminder::TYPE_EMAIL)
            ->orderBy('remind_at', 'asc')
            ->get();

        foreach ($reminders as $reminder) {
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

            ++$count;
        }

        $this->info("$count reminder(s) sent");
    }
}
