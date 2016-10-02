<?php

return [
    'btn-next' => 'Dalej',
    'database' => [
        'success' => 'Baza danych została utworzona i wypełniona. To już prawie koniec!',
        'title' => 'Baza danych',
    ],
    'environment' => [
        'content' => 'Najpierw ustawimy podstawową konfigurację skryptu - informacje niezbędne do połączenia z bazą danych.',
        'db-host' => 'Host',
        'db-host-help' => 'Najczęściej <code>localhost</code>',
        'db-name' => 'Nazwa bazy',
        'db-password' => 'Hasło',
        'db-user' => 'Użytkownik',
        'db-prefix' => 'Prefiks tabel',
        'db-prefix-help' => 'Zalecany. Pozwala uniknąć konfliktów przy kilku równoległych instalacjach w tej samej bazie.',
        'other' => 'Pozostałe',
        'timezone' => 'Strefa czasowa',
        'title' => 'Konfiguracja skryptu',
    ],
    'final' => [
        'content' => 'Gratulacje! Instalacja Codice została zakończona.',
        'login' => 'Logowanie',
        'title' => 'Koniec',
        'unlink-failed' => 'Koniecznie usuń plik <code>storage/app/.install-pending</code>!',
    ],
    'requirements' => [
        'content' => 'Szybkie sprawdzenie czy Twój serwer spełnia wymagania skryptu.',
        'directory' => 'Katalog',
        'error-extensions' => 'Niektóre z wymaganych rozszerzeń nie zostały znalezione.',
        'error-directories' => 'Niektóre z wymaganych katalogów nie są zapisywalne.',
        'extension' => 'Rozszerzenie',
        'status' => 'Status',
        'status-dir-error' => 'Nie można zapisać',
        'status-dir-ok' => 'Zapisywalny',
        'status-ext-error' => 'Niedostępne',
        'status-ext-ok' => 'Dostępne',
        'title' => 'Wymagania',
    ],
    'step' => 'Krok',
    'title' => 'Instalacja Codice',
    'user' => [
        'content' => 'Na koniec utworzymy Twoje konto użytkownika',
        'email' => 'Adres email',
        'name' => 'Nazwa użytkownika',
        'password' => 'Hasło',
        'password-confirmation' => 'Hasło (potwierdź)',
        'title' => 'Użytkownik',
    ],
    'welcome' => [
        'title' => 'Witaj',
        'para1' => 'Witaj w instalatorze Codice!',
        'para2' => 'Przejdź przez kolejne etapy, aby zainstalować skrypt na Twoim serwerze.',
    ],
    // Name of the label assigned to welcome note
    'welcome-note-label' => 'Ważne',
];
