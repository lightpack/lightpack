<?php

return [
    /**
     * Environment.
     */
    'environment' => get_env('APP_ENV', 'development'),

    /**
     * Site.
     */
    'site' => [
        'url' => get_env('APP_URL', 'http://localhost'),
        'timezone' => 'UTC',
        'locale' => 'en',
        'default_locale' => 'en',
    ],

    /**
     * Cookie.
     */
    'cookie' => [
        'secret' => get_env('COOKIE_SECRET'),
    ],

    /**
     * URL.
     */
    'url' => [
        'api_route_prefix' => 'api',
        'admin_route_prefix' => 'admin',
    ],

    /**
     * Modules.
     */
    'modules' => [],

    /**
     * Connection.
     */
    'connection' => [
        'sqlite' => [
            'database' => get_env('SQLITE_DB_PATH'),
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

    /**
     * Cache.
     */
    'cache' => [
        'storage' => DIR_STORAGE . '/cache',
    ],

    /**
     * Logger.
     */
    'logger' => [
        'filename' => DIR_STORAGE . '/logs.txt',
    ],
];
