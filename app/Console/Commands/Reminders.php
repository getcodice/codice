<?php

namespace Codice\Console\Commands;

use Codice\Reminder;
use Codice\Reminders\ReminderService;
use Illuminate\Console\Command;

class Reminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send queued reminders';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;

        // Get all reminders which haven't been handled yet
        $reminders = Reminder::whereRaw('remind_at < NOW()')
            ->orderBy('remind_at', 'asc')
            ->get();

        foreach ($reminders as $reminder) {
            ReminderService::get($reminder->type)->cron($reminder);

            ++$count;
        }

        $this->info("$count reminder(s) sent");
    }
}
