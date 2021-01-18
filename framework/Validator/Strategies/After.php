<?php

namespace Lightpack\Validator\Strategies;

use DateTime;
use Lightpack\Validator\StringTrait;
use Lightpack\Validator\IValidationStrategy;

class After implements IValidationStrategy
{
    use StringTrait;
    
    private $_errorType = 'date';
    private $_afterDate;
    private $_dateFormat;
    
    public function validate($data, $string)
    {
        list($this->_dateFormat, $this->_afterDate) = $this->explodeString($this->stringReplace('/', '', $string), ',');
    
        if(($data = DateTime::createFromFormat($this->_dateFormat, $data)) === false)
		{
            $this->_errorType = 'format';
			return false;
		}

		return ($data->getTimestamp() > DateTime::createFromFormat($this->_dateFormat, $this->_afterDate)->getTimestamp());
    }
    
    public function getErrorMessage($field)
    {
        if($this->_errorType === 'format') {
            $message = sprintf("%s must match format: %s", $field, $this->_dateFormat);
        } else {
            $message = sprintf("%s must be after %s", $field, $this->_afterDate);
        }
        return $message;
    }
}