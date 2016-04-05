<?php

return [
    'btn-next' => 'Next',
    'database' => [
        'success' => 'Database have been created and filled in. It\'s almost done!',
        'title' => 'Database',
    ],
    'environment' => [
        'content' => 'First you need to provide basic script configuration - informations for connecting to database.',
        'db-host' => 'Host',
        'db-host-help' => 'Usually just <code>localhost</code>',
        'db-name' => 'Database name',
        'db-password' => 'Password',
        'db-user' => 'Username',
        'title' => 'Script configuration',
    ],
    'final' => [
        'content' => 'Congratulations! Codice is set up now.',
        'login' => 'Login',
        'title' => 'Finish',
        'unlink-failed' => 'You NEED to remove <code>storage/app/.install-pending</code> file!',
    ],
    'requirements' => [
        'content' => 'Quick check of script requirements.',
        'directory' => 'Directory',
        'error-extensions' => 'Some of required extensions have not been found.',
        'error-directories' => 'Some of required directories are not writable.',
        'extension' => 'Extension',
        'status' => 'Status',
        'status-dir-error' => 'Not writable',
        'status-dir-ok' => 'Writable',
        'status-ext-error' => 'Unavailable',
        'status-ext-ok' => 'Available',
        'title' => 'Requirements',
    ],
    'step' => 'Step',
    'title' => 'Codice installer',
    'user' => [
        'content' => 'Finally, we need to create your account in the system',
        'email' => 'E-mail address',
        'name' => 'Username',
        'password' => 'Password',
        'password-confirmation' => 'Password (confirm)',
        'title' => 'User account',
    ],
    'welcome' => [
        'title' => 'Welcome',
        'para1' => 'Welcome to Codice installer!',
        'para2' => 'Go through few steps to install a script on your server.',
    ],
    // Name of the label assigned to welcome note
    'welcome-note-label' => 'Important',
];
