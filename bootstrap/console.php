<?php

/**
 * ------------------------------------------------------------
 * Confirm that it runs in CLI environment.
 * ------------------------------------------------------------
 */

if (PHP_SAPI !== 'cli') {
    die("Abort: CLI is not supported in php-cgi environment!\n");
}

/**
 * ------------------------------------------------------------
 * Use Composer Autoloader.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * ------------------------------------------------------------
 * Require Defined Constants
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/constants.php';

/**
 * ------------------------------------------------------------
 * Configure Environment Settings
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/environment.php';

/**
 * ------------------------------------------------------------
 * Bind Providers in IoC Container.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/providers.php';

/**
 * ------------------------------------------------------------
 * Configure Error/Exception Reporting.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/debug.php';

/**
 * ------------------------------------------------------------
 * Bootstrap framework specific commands.
 * ------------------------------------------------------------
 */

Lightpack\Console\Console::bootstrap();

/**
 * ------------------------------------------------------------
 * Discover app events.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/events.php';

/**
 * ------------------------------------------------------------
 * Register application specific console commands.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/commands.php';


/**
 * ------------------------------------------------------------
 * Run the console command.
 * ------------------------------------------------------------
 */

Lightpack\Console\Runner::run();
