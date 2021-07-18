<?php

return [
    /**
     * Application settings.
     */

    'APP_ENV' => 'development',
    'APP_URL' => 'http://localhost',
    'ASSET_URL' => 'http://localhost/assets',

    /**
     * Database driver.
     */

    'DB_DRIVER' => 'mysql',
    
    /**
     * MySQL settings.
     */

    'DB_HOST' => 'localhost',
    'DB_PORT' => 3306,
    'DB_NAME' => '',
    'DB_USER' => '',
    'DB_PSWD' => '',

    /**
     * SQLite settings.
     * 
     * You should pass an absolute path to your SQLite database
     * file else it will default to in-memory database.
     */

    'SQLITE_DB_PATH' => ':memory',

    /**
     * Session settings.
     */

    'SESSION_NAME' => 'lightpack', 

    /**
     * Cookie settings.
     */

    'COOKIE_SECRET' => '!@Secret4Cookie@!',

    /**
     * Log driver: file, null.
     */

    'LOG_DRIVER' => 'file',
];