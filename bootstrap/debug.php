<?php

use Lightpack\Debug\Handler;
use Lightpack\Debug\ExceptionRenderer;

/**
 * Turn on/off display errors in browser.
 */

ini_set('display_errors', 'on');

/**
 * Turn on/off PHP startup errors.
 */
ini_set('display_startup_errors', 'off');

/**
 * Let us report all possible PHP errors.
 */
error_reporting(E_ALL);

/**
 * Instantiate framework debug handler.
 */

$handler = new Handler(
    $container->get('logger'),
    new ExceptionRenderer(
        get_env('APP_ENV', 'development')
    )
);

/**
 * Register custom exception handler.
 */
set_exception_handler([$handler, 'handleException']);

/**
 * Register custom error exceptions handler.
 */
set_error_handler([$handler, 'handleError']);

/**
 * Register shutdown function to deal with fatal situations.
 */
register_shutdown_function([$handler, 'handleShutdown']);
