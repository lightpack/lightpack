<?php

namespace App\Console\Framework;

class Commands
{
    private static $options = [];

    public static function register(string $command, Command $handler)
    {
        self::$options[$command] = $handler;
    }

    public static function getCommandHandler(string $command)
    {
        if(!isset(self::$options[$command])) {
            throw new \Exception('Console command not found: ' . $command); 
        }

        return self::$options[$command];
    }
}