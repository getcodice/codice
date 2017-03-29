<?php

use Carbon\Carbon;
use Codice\Label;
use Codice\Note;
use Codice\Plugins\Action;

Action::register('user.created', 'codice_add_welcome_note', function($parameters) {
    $locale = $parameters['user']->options['language'];

    $path = base_path("resources/lang/$locale/welcome.md");

    if (!file_exists($path)) {
        $path = base_path('resources/lang/en/welcome.md');
    }

    $note = new Note;
    $note->user_id = $parameters['user']->id;
    $note->content = file_get_contents($path);
    $note->expires_at = Carbon::tomorrow();
    $note->status = 1;
    $note->save();

    $label = new Label;
    $label->user_id = $parameters['user']->id;
    $label->name = trans('install.welcome-note-label', [], $locale);
    $label->color = 6;
    $label->save();

    $note->labels()->attach($label);
});
