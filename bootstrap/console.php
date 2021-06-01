<?php

use App\Console\Framework\Commands;

$consoleConfig = require_once DIR_CONFIG  . '/console.php';

foreach($consoleConfig as $command => $handler) {
    Commands::register($command, new $handler);
}