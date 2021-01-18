<?php

namespace Lightpack\Validator;

/**
 * An interface to implemented by all our validation strategy classes.
 */
interface IValidationStrategy
{
    /**
     * The method to be called to perform validation on data.
     *
     * @access public
     * @param  string  $data   The actual data to validate.
     * @param  string  $param  The value to validate against.
     */
    public function validate($data, $param);
    
    /**
     * The method to be called to access the generated error message.
     *
     * @access public
     * @param   string  $field  The field for which the error message is required.
     * @return  string
     */
    public function getErrorMessage($field);
}