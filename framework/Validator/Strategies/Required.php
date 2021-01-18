<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Required implements IValidationStrategy
{
    public function validate($data, $param = null)
    {
        return trim($data) !== '';
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s is required", $field);
    }
}