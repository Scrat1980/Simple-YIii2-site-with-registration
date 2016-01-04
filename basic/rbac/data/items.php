<?php
return [
    'updateUser' => [
        'type' => 2,
        'description' => 'Update a user',
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'Delete a user',
    ],
    'viewUser' => [
        'type' => 2,
        'description' => 'View a user',
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'viewUser',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'updateUser',
            'deleteUser',
            'user',
        ],
    ],
];
