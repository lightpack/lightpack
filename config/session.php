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
    ],
];
