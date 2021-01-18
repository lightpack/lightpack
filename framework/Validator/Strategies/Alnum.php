<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Alnum implements IValidationStrategy
{   
    public function validate($data, $param = null)
    {
        return ctype_alnum($data);
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s must contain only alphabets and numbers", $field);
    }
}