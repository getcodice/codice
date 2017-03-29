<?php

use Carbon\Carbon;
use Codice\Label;
use Codice\Note;
use Codice\Plugins\Action;
use Codice\Support\Markdown\Table\Extension as TableExtension;
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;

Action::register('note.saving', 'codice_note_parse_markdown', function($parameters) {
    $content = $parameters['note']->content;

    $environment = Environment::createCommonMarkEnvironment();
    $environment->addExtension(new TableExtension());

    $converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

    $contentParsed = $converter->convertToHtml($content);

    $parameters['note']->content_raw = $content;
    $parameters['note']->content = $contentParsed;
});

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
