<?php

return [
    'create' => [
        'title' => 'New label',
        'submit' => 'Add',
        'success' => 'Label has been created.',
    ],
    // This array maps stores label colors' names. See also config/labels.php
    // for mappings to the respective bootstrap classes
    'colors' => [
        1 => 'grey',
        2 => 'blue',
        3 => 'green',
        4 => 'navy',
        5 => 'orange',
        6 => 'red',
    ],
    'edit' => [
        'title' => 'Edit label',
        'submit' => 'Save',
        'success' => 'Label has been edited.',
    ],
    'index' => [
        'controls' => 'Controls',
        'count' => 'Notes',
        'create' => 'Create new',
        'edit' => 'Edit',
        'name' => 'Name',
        'remove' => 'Remove',
        'title' => 'Labels',
    ],
    'labels' => [
        'color' => 'Color',
        'name' => 'Name',
    ],
    'notes' => [
        'page-heading' => '<em>:label</em> label',
        'title' => '":label" label',
    ],
    'not-found' => 'Label not found',
    'removed' => 'Label has been removed.',
];
