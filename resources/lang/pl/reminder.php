<?php

return [
    'email' => [
        'content' => 'Witaj, :name!<br /><br />Poniższe zadanie oczekuje na wykonanie do <b>:expires</b>',
        'subject' => '[Codice] Przypomnienie o zadaniu',
    ],
    'index' => [
        'controls' => 'Akcje',
        'note-id' => 'ID notatki',
        'note-link' => 'Notatka #:id',
        'remind-at' => 'Czas przypomnienia',
        'remove' => 'Usuń',
        'title' => 'Przypomnienia',
        'type' => 'Typ przypomnienia',
    ],
    'not-found' => 'Nie znaleziono przypomnienia',
    'type' => [
        'email' => 'Wiadomość email',
        'sms' => 'SMS',
    ],
    'removed' => 'Przypomnienie zostało usunięte',
];
