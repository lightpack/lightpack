<?php

/**
 * Storage paths.
 */

return [
    'storage.cache' => DIR_STORAGE . '/cache',
    'storage.public' => DIR_STORAGE . '/uploads/public',
    'storage.logs' => DIR_STORAGE . '/logs',
    'storage.logs.max_file_size' => 10*1024*1024, // 10mb
    'storage.logs.max_log_files' => 10,
];