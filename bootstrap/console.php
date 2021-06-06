<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constants.php';

use App\Console\Console;

$consoleConfig = require_once DIR_CONFIG  . '/console.php';

foreach($consoleConfig as $command => $handler) {
    Console::register($command, new $handler);
}