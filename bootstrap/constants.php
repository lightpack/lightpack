<?php

/**
 * ------------------------------------------------------------
 * App Environment
 * ------------------------------------------------------------
 */

define('APP_ENV', 'development');

/**
 * ------------------------------------------------------------
 * Development Environment
 * ------------------------------------------------------------
 */

define('DEV_ENV', APP_ENV === 'development' ? true : false);

/**
 * ------------------------------------------------------------
 * Define directory constants.
 * ------------------------------------------------------------
 */

define('DIR_ROOT', __DIR__ . '/..');
define('DIR_APP', DIR_ROOT . '/app');
define('DIR_VIEWS', DIR_APP . '/views');
define('DIR_CONFIG', DIR_ROOT . '/config');
define('DIR_MODULES', DIR_ROOT . '/modules');
define('DIR_BOOTSTRAP', DIR_ROOT . '/bootstrap');
define('DIR_STORAGE', DIR_ROOT . '/storage');

/**
 * ------------------------------------------------------------
 * Set session name.
 * ------------------------------------------------------------
 */

define('SESSION_NAME', 'lightpack'); 