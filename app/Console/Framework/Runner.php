<?php

namespace App\Console\Framework;

class Runner
{
    public static function run()
    {
        global $argv;
        $command = $argv[1] ?? null;
        $console = new Console;
        $console->runCommand($command);
    }
}