<?php

namespace Lightpack\Exceptions;

class FilterNotFoundException extends \Exception 
{
    public function __construct(string $message) 
    {
        parent::__construct($message, 500);
    }
}