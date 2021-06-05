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
            fputs(STDERR, "Invalid command: {$command}\n");
            exit(0);
        }

        return self::$options[$command];
    }
}