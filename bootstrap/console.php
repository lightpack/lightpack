<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constants.php';

use App\Console\Framework\Commands;

$consoleConfig = require_once DIR_CONFIG  . '/console.php';

foreach($consoleConfig as $command => $handler) {
    Commands::register($command, new $handler);
}