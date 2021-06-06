<?php

namespace App\Console;

class HelpCommand implements ICommand
{
    public function run()
    {
        echo "I am here to help you :)\n";
    }
}
