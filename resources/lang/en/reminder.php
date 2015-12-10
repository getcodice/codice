<?php

return [
    'email' => [
        'content' => 'Hello, :name!<br /><br />Following task expires on <b>:expires</b>',
        'subject' => '[Codice] Task reminder',
    ],
    'index' => [
        'controls' => 'Controls',
        'note-id' => 'Note ID',
        'note-link' => 'Note #:id',
        'remind-at' => 'Remind at',
        'remove' => 'Remove',
        'title' => 'Reminders',
        'type' => 'Reminder type',
    ],
    'none' => [
        'content' => 'There are no reminders scheduled',
        'title' => 'No reminders',
    ],
    'not-found' => 'Reminder not found',
    'type' => [
        'email' => 'Email message',
        'sms' => 'SMS',
    ],
    'removed' => 'Reminder have been removed',
];
