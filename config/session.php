<?php

/**
 * ------------------------------------------------------------
 * Session settings.
 * ------------------------------------------------------------
 */

return [
    'session' => [
        'driver' => get_env('SESSION_DRIVER', 'default'),
        'name' => get_env('SESSION_NAME', 'lightpack_session'),
        'lifetime' => get_env('SESSION_LIFETIME', 7200), // 2 hrs
        'same_site' => get_env('SESSION_SAME_SITE', 'lax'),
        'https' => get_env('SESSION_HTTPS', false),
        'http_only' => get_env('SESSION_HTTP_ONLY', true),
        'encrypt' => get_env('SESSION_ENCRYPT', false),
    ]
];
