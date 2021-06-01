<?php

namespace App\Console;

use App\Console\Framework\Command;

class CreateController extends Command
{
    public function __construct()
    {
        $this
            ->register('h')
            ->alias('help')
            ->help('Flag -h or --help shows help manual.');
    }

    public function run()
    {
        echo 'I am here to help you :)';
    }
}