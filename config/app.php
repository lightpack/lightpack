<?php

return [
    'app' => [
        'env' => get_env('APP_ENV', 'development'),
        'url' => get_env('APP_URL', 'http://127.0.0.1'),
        'timezone' => 'UTC',
        'key' => get_env('APP_KEY'),
        'lang' => [
            'default' => get_env('APP_LOCALE', 'en'),
            'fallback' => get_env('APP_FALLBACK_LOCALE', 'en'),
        ],
    ],
];
