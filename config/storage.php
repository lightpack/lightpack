<?php

/**
 * Storage paths.
 */

return [
    'storage' => [
        'driver' => get_env('STORAGE_DRIVER', 'local'), // s3

        // S3-specific configuration (only needed for 's3' driver)
        's3' => [
            'key' => get_env('AWS_ACCESS_KEY'),
            'secret' => get_env('AWS_SECRET_KEY'),
            'region' => get_env('AWS_REGION', 'us-east-1'),
            'bucket' => get_env('AWS_BUCKET'),
            'prefix' => '', // Optional prefix for all files
            'endpoint' => get_env('AWS_ENDPOINT'), // Optional, for S3-compatible services
            'use_path_style_endpoint' => false, // Optional, for S3-compatible services,
            'cloudfront' => [
                'domain' => get_env('CLOUDFRONT_DOMAIN'),
                'key_pair_id' => get_env('CLOUDFRONT_KEY_PAIR_ID'),
                'private_key' => get_env('CLOUDFRONT_PRIVATE_KEY_PATH'),
            ],
        ],

        // other storage paths
        'cache' => DIR_STORAGE . '/cache',
        'public' => DIR_STORAGE . '/uploads/public',
        'logs' => [
            'path' => DIR_STORAGE . '/logs',
            'max_file_size' => 500 * 1024 * 1024, // 10mb
            'max_log_files' => 10,
        ]
    ]
];
