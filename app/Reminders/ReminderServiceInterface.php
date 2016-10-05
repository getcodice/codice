<?php

namespace Codice\Reminders;

use Codice\Note;
use Codice\Reminder;

interface ReminderServiceInterface {
    /**
     * Set new reminder for a given note.
     */
    public function set(Note $note, $input, $data = []);

    /**
     * Change given reminder.
     */
    public function change(Reminder $reminder, $input, $data = []);

    /**
     * Cancel given reminder.
     */
    public function cancel($id);

    /**
     * Perform operations using cron. Method can be left empty.
     */
    public function cron(Reminder $reminder);

    /**
     * Return reminder's human readable name.
     */
    public function getName();
}
