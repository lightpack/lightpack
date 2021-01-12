<?php

namespace Lightpack\Exceptions;

class InvalidCsrfTokenException extends \Exception 
{
    public function __construct(string $message) 
    {
        parent::__construct($message, 403);
    }
}