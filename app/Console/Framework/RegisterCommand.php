<?php

namespace App\Console\Framework;

abstract class RegisterCommand
{
    private $shortFlag;
    private $longFlag;
    private $required;
    private $helpText;

    abstract public function run();

    public function register(string $shortFlag)
    {
        $this->shortFlag = $shortFlag;

        return $this;
    }

    public function alias(string $longFlag = null)
    {
        $this->longFlag = $longFlag;
        
        return $this;
    }

    public function required(bool $required = false)
    {
        $this->required = $required;
        
        return $this;
    }

    public function help(string $helpText = null)
    {
        $this->helpText = $helpText;
        
        return $this;
    }
}