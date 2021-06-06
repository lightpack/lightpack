<?php

namespace App\Console;

class Runner
{
    public static function run()
    {
        global $argv;

        $command = $argv[1] ?? null;
        $arguments = $argv;

        array_shift($arguments);

        if(count($arguments) >= 1) {
            array_shift($arguments);
        }

        Console::runCommand($command, $arguments);
    }
}