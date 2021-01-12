<?php

namespace Lightpack\Exceptions;

class RouteNotFoundException extends \Exception 
{
    public function __construct(string $message) 
    {
        parent::__construct($message, 404);
    }
}