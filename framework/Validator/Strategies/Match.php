<?php

namespace Lightpack\Validator\Strategies;

use Lightpack\Validator\StringTrait;
use Lightpack\Validator\IValidationStrategy;

class Match implements IValidationStrategy
{
    
    use StringTrait;
    
    private $_matchTo;
    
    public function validate($data, $matchString)
    {
        list($this->_matchTo, $matchValue) = $this->explodeString($matchString, '=');
        return $data === $matchValue;
    }
    
    public function getErrorMessage($field)
    {
        return sprintf("%s mismatch", $this->_matchTo);
    }
}