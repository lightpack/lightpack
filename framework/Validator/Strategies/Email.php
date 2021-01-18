<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Email implements IValidationStrategy
{   
    public function validate($data, $param = null)
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s is invalid", $field);
    }
}