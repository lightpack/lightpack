<?php

/**
 * Turn off display errors in browser.
 */
ini_set('display_errors', 'off');

/**
 * Turn off PHP startup errors.
 */
ini_set('display_startup_errors', 1); 

/**
 * Let us report all possible PHP errors.
 */
error_reporting(E_ALL);

/**
 * Instantiate framework debug handler.
 */
$handler = new Framework\Debug\Handler(
    new Framework\Debug\ExceptionRenderer(APP_ENV)
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