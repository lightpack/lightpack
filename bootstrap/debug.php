<?php

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
Lightpack\App::bootDebugHandler();
