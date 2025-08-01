<?php

return [
    'redis' => [
        /**
         * Default Redis Settings: 
         * Configure your Redis connection settings here. These settings will be used
         * by the Redis client to establish connections to your Redis server.
         */
        'default' => [
            'host' => get_env('REDIS_HOST') ?? '127.0.0.1',
            'port' => get_env('REDIS_PORT') ?? 6379,
            'password' => get_env('REDIS_PASSWORD') ?? null,
            'database' => get_env('REDIS_DB') ?? 0,
            'timeout' => get_env('REDIS_TIMEOUT') ?? 0.0,
            'read_timeout' => get_env('REDIS_READ_TIMEOUT') ?? 0.0,
            'retry_interval' => get_env('REDIS_RETRY_INTERVAL') ?? 0,
            'prefix' => get_env('REDIS_PREFIX') ?? '',
        ],

        /**
         * Redis Cache Configuration:
         * Configure Redis cache settings. These will be used when the cache driver
         * is set to 'redis' in your cache configuration.
         */
        'cache' => [
            'connection' => 'default',
            'prefix' => 'cache:',
        ],

        /**
         * Redis Session Configuration:
         * Configure Redis session settings. These will be used when the session driver
         * is set to 'redis' in your session configuration.
         */
        'session' => [
            'connection' => 'default',
            'prefix' => 'session:',
            'lifetime' => get_env('SESSION_LIFETIME', 7200), // 2 hrs
        ],

        /**
         * Redis Job Queue Configuration:
         * Configure Redis job queue settings. These will be used when the job engine
         * is set to 'redis' in your environment configuration.
         */
        'jobs' => [
            'connection' => 'default',
            'prefix' => get_env('REDIS_JOB_PREFIX') ?? 'jobs:',
            'database' => get_env('REDIS_JOB_DB') ?? 0,
        ],
    ],
];
