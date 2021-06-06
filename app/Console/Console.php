<?php

namespace App\Console;

class Console
{
    private static $commands = [];

    public static function register(string $command, ICommand $handler)
    {
        self::$commands[$command] = $handler;
    }

    public static function getCommandHandler(string $command)
    {
        if (!isset(self::$commands[$command])) {
            fputs(STDERR, "Invalid command: {$command}\n");
            exit(0);
        }

        return self::$commands[$command];
    }

    public static function runCommand(string $command = null, array $arguments = [])
    {
        if ($command === null) {
            fputs(STDOUT, "Need help?\n");
            exit(0);
        }

        $handler = self::getCommandHandler($command);
        $handler->run($arguments);
    }
}
