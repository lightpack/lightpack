<?php

return [
    'cable' => [
        /**
         * Available drivers: database, redis
         */
        'driver' => get_env('CABLE_DRIVER', 'database'),

        /**
         * Database driver
         */
        'database' => [
            'table' => 'cable_messages',
            'cleanup_older_than' => 86400, // 24 hours
        ],

        /**
         * Redis driver
         */
        'redis' => [
            'prefix' => 'cable:',
            'ttl' => 86400, // 24 hours
        ],

        /**
         * Client settings
         */
        'client' => [
            'poll_interval' => 100, // milliseconds
            'reconnect_interval' => 5000, // milliseconds
            'max_reconnect_attempts' => 5,
        ],

        /**
         * Presence Channel Configuration: 
         * Presence channels allow you to track which users are online in real-time.
         */
        'presence' => [
            'driver' => get_env('CABLE_PRESENCE_DRIVER', 'database'),

            'database' => [
                'table' => 'cable_presence',
                'timeout' => 30, // seconds
            ],

            'redis' => [
                'prefix' => 'cable:presence:',
                'timeout' => 30, // seconds
            ],
        ],
    ],
];
