<?php

/**
 * ------------------------------------------------------------
 * Register app console commands.
 * ------------------------------------------------------------
 */

$commands = require DIR_CONFIG . '/commands.php';
$commands = $commands['commands'];

foreach ($commands as $command => $handler) {
    Lightpack\Console\Console::register(
        $command,
        new $handler
    );
}
