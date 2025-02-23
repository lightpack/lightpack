<?php

return [
    'db' => [
        'driver' => get_env('DB_DRIVER'),
        'mysql' => [
            'host' => get_env('DB_HOST'),
            'port' => get_env('DB_PORT'),
            'username' => get_env('DB_USER'),
            'password' => get_env('DB_PSWD'),
            'database' => get_env('DB_NAME'),
            'options' => null,
        ],
    ],
];
