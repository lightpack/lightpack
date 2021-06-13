<?php

require_once __DIR__ . '/constants.php';

use Lightpack\Console\Console;

/**
 * Register application specific console commands.
 */
$consoleConfig = require_once DIR_CONFIG  . '/console.php';

foreach($consoleConfig as $command => $handler) {
    Console::register($command, new $handler);
}