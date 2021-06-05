<?php

namespace App\Console;

use App\Console\Framework\Command;

class ControllerCommand extends Command
{
    public function run(array $arguments = [])
    {
        echo "Let us create a controller.\n";
        print_r($arguments);
    }
}
