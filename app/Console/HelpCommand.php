<?php

namespace App\Console;

use App\Console\Framework\Command;

class HelpCommand extends Command
{
    public function run()
    {
        echo "I am here to help you :)\n";
    }
}
