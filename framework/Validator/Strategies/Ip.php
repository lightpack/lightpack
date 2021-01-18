<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Ip implements IValidationStrategy
{   
    public function validate($data, $param = null)
    {
        return (bool) filter_var($data, FILTER_VALIDATE_IP);
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s is invalid", $field);
    }
}