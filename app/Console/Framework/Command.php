<?php

namespace App\Console\Framework;

class Command
{
    public function register(string $shortFlag)
    {
        return $this;
    }

    public function alias(string $longFlag = null)
    {
        return $this;
    }

    public function required(bool $required = false)
    {

    }

    public function help(string $helpText = null)
    {

    }
}