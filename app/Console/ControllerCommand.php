<?php

namespace App\Console;

use App\Console\ICommand;

class ControllerCommand implements ICommand
{
    public function run(array $arguments = [])
    {
        echo "Let us create a controller.\n";
        print_r($arguments);
    }
}
