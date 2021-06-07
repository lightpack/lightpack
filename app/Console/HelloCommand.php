<?php

namespace App\Console;

use Lightpack\Console\ICommand;

class HelloCommand implements ICommand
{
    public function run(array $arguments = [])
    {
        $message = 'Hello ' . ($arguments[0] ?? 'World');
        $message .= "\n";
        
        fputs(STDOUT, $message);
    }
}
