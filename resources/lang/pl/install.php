<?php

return [
    'btn-next' => 'Dalej',
    'database' => [
        'success' => 'Baza danych została utworzona i wypełniona. To już prawie koniec!',
        'title' => 'Baza danych',
    ],
    'environment' => [
        'content' => 'Najpierw ustawimy podstawową konfigurację skryptu - informacje niezbędne do połączenia z bazą danych.',
        'db' => 'Połączenie z bazą',
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
        'do-unlink' => 'Instalacja zakończona powodzeniem! Usuń plik <code>storage/app/.install-pending</code>.',
        'success' => 'Instalacja zakończona powodzeniem!',
    ],
    'requirements' => [
        'available' => 'Dostępne',
        'content' => 'Szybkie sprawdzenie czy Twój serwer spełnia wymagania skryptu.',
        'directory' => 'Katalog',
        'error-extensions' => 'Niektóre z wymaganych rozszerzeń nie zostały znalezione.',
        'error-directories' => 'Niektóre z wymaganych katalogów nie są zapisywalne.',
        'error-software' => 'Zainstalowana wersja PHP nie spełnia wymagań',
        'extension' => 'Rozszerzenie',
        'software' => 'Oprogramowanie',
        'status' => 'Status',
        'status-dir-error' => 'Nie można zapisać',
        'status-dir-ok' => 'Zapisywalny',
        'title' => 'Wymagania',
        'unavailable' => 'Niedostępne',
    ],
    'step' => 'Krok',
    'title' => 'Instalacja Codice',
    'user' => [
        'content' => 'Na koniec utworzymy Twoje konto użytkownika',
        'email' => 'Adres email',
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
