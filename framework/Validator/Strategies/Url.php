<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\IValidationStrategy;

class Url implements IValidationStrategy
{   
    private $_validUrlPrefixes = array('http://', 'https://', 'ftp://');
    
    public function validate($data, $param = null)
    {
        foreach ($this->_validUrlPrefixes as $prefix) {
            if (strpos($data, $prefix) !== false) {
                return filter_var($data, FILTER_VALIDATE_URL);
            }
        }

        return false;
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s appears to be invalid", $field);
    }
}