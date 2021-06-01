<?php

namespace App\Console\Framework;

class Runner
{
    public static function run()
    {
        $console = new Console;
        $console->runCommand();
    }
}