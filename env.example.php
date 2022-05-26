<?php

return [
    /**
     * Application settings.
     */

    'APP_ENV' => 'development',
    'APP_URL' => 'http://localhost',
    'ASSET_URL' => 'http://localhost/assets',
    'APP_DEBUG' => true,

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

    'SESSION_NAME' => 'sess_testmocks_admin',
    'SESSION_DRIVER' => 'default',

    /**
     * Cookie settings.
     */

    'COOKIE_SECRET' => '!@Secret4Cookie@!',

    /**
     * Log driver: file, null.
     */

    'LOG_DRIVER' => 'file',

    /**
     * Mail settings.
     */
    
    'MAIL_DRIVER' => 'smtp',
    'MAIL_HOST' => 'smtp.mailtrap.io',
    'MAIL_PORT' => 587,
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_USERNAME' => null,
    'MAIL_PASSWORD' => null,
    'MAIL_FROM_ADDRESS' => 'lightpack@example.com',
    'MAIL_FROM_NAME' => 'Lightpack',
];