<?php

namespace App\Console\Framework;

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

        $console = new Console;
        $console->runCommand($command, $arguments);
    }
}