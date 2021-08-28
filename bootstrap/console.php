<?php

require_once __DIR__ . '/constants.php';

use Lightpack\Console\Console;

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

require_once DIR_BOOTSTRAP . '/providers.php';

/**
 * ------------------------------------------------------------
 * Discover app events.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/events.php';

/**
 * Register application specific console commands.
 */
$consoleConfig = require_once DIR_CONFIG  . '/console.php';

foreach ($consoleConfig as $command => $handler) {
    Console::register($command, new $handler);
}
