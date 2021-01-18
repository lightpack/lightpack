<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Regex implements IValidationStrategy
{   
    public function validate($data, $regex)
    {
        return (bool) preg_match($regex, $data);
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s is invalid", $field);
    }
}