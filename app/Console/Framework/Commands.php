<?php

namespace App\Console\Framework;

class Commands
{
    private static $options = [];

    public static function register(string $command, Command $handler)
    {
        self::$options[$command] = $handler;
    }
}