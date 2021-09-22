<?php

/**
 * ------------------------------------------------------------
 * Register app console commands.
 * ------------------------------------------------------------
 */

foreach (config('commands') as $command => $handler) {
    Lightpack\Console\Console::register(
        $command,
        new $handler
    );
}
