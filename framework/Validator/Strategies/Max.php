<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Max implements IValidationStrategy
{
    private $_length;
    
    public function validate($data, $num)
    {
        $this->_length = $num;
        
        return strlen($data) <= $num;  
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s must be <= %s", $field, $this->_length);
    }
}