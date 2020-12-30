<?php

return [
    'environment' => 'development',
    'site' => [
        'url' => 'http://localhost',
        'timezone' => 'UTC',
        'locale' => 'en',
        'default_locale' => 'en',
        'log_errors' => true,
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
            'host' => 'localhost',
            'port' => 3306,
            'username' => 'root',
            'password' => '',
            'database' => '',
            'options' => null,
        ]
    ],
];