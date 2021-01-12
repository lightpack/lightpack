<?php

namespace Lightpack\Exceptions;

class ControllerNotFoundException extends \Exception 
{
    public function __construct(string $message) 
    {
        parent::__construct($message, 500);
    }
}