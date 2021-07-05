<?php

return [
    /**
     * Sqlite.
     */
    'db.sqlite.database' => get_env('SQLITE_DB_PATH'),

    /**
     * MySQL.
     */
    'db.mysql.host' => get_env('DB_HOST'),
    'db.mysql.port' => get_env('DB_PORT'),
    'db.mysql.username' => get_env('DB_USER'),
    'db.mysql.password' => get_env('DB_PSWD'),
    'db.mysql.database' => get_env('DB_NAME'),
    'db.mysql.options' => null,
];
