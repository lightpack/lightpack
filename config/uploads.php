<?php

return [
    'uploads' => [
        'queue' => get_env('UPLOADS_QUEUE', 'default'),
        'max_attempts' => get_env('UPLOADS_MAX_ATTEMPT', 1),
        'retry_after' => get_env('UPLOADS_RETRY_AFTER', '10 seconds'),
    ],
];
