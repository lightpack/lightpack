<?php

return [
    'environment' => get_env('APP_ENV', 'development'),
    'site' => [
        'url' => 'http://localhost',
        'timezone' => 'UTC',
        'locale' => 'en',
        'default_locale' => 'en',
    ],
    'cookie' => [
        'secret' => '!@Secret4Cookie@!',
    ],
    'url' => [
        'api_route_prefix' => 'api',
        'admin_route_prefix' => 'admin',
    ],
    'modules' => [
        
    ],
    'connection' => [
        'sqlite' => [
            'database' => '',
        ],
        'mysql' => [
            'host' => get_env('DB_HOST'), 
            'port' => get_env('DB_PORT'),
            'username' => get_env('DB_USER'),
            'password' => get_env('DB_PSWD'), 
            'database' => get_env('DB_NAME'),
            'options' => null,
        ]
    ],
    'cache' => [
        'storage' => DIR_STORAGE . '/cache',
    ],
    'logger' => [
        'filename' => DIR_STORAGE . '/logs.txt',
    ],
];