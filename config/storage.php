<?php

/**
 * Storage paths.
 */

return [
    'storage' => [
        'cache' => DIR_STORAGE . '/cache',
        'public' => DIR_STORAGE . '/uploads/public',
        'logs' => DIR_STORAGE . '/logs',
        'logs.max_file_size' => 10 * 1024 * 1024, // 10mb
        'logs.max_log_files' => 10,
    ]
];
