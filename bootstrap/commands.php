<?php

/**
 * ------------------------------------------------------------
 * List console commands here.
 * ------------------------------------------------------------
 */

$commands = [
    // 'test:hello' => App\Commands\HelloCommand::class,
];

/**
 * ------------------------------------------------------------
 * Register app console commands.
 * ------------------------------------------------------------
 */

foreach ($commands as $command => $handler) {
    Lightpack\Console\Console::register(
        $command,
        new $handler
    );
}
