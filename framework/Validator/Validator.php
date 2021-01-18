<?php

namespace Lightpack\Validator;

use RuntimeException;
use Lightpack\Validator\AbstractValidator;

/**
 * This is the main class to be exposed to the end user. It extends the core
 * functionalities from Lightpack\Validator\AbstractClass.
 *
 * Method Prototypes:
 * 
 * void      public   function    __construct(array $dataSource);
 * void      public   function    setRule(string $key, string $rules);
 * void      public   function    run(void);
 * boolean   public   function    hasErrors(void);
 * string    public   function    getError(string $field);
 * array     public   function    getErrors(void);
 */
class Validator extends AbstractValidator
{   
    /**
     * Class constructor.
     *
     * @access  public
     * @param   array  $dataSource  Array to validate
     */
    public function __construct(array $dataSource)
    {
        parent::__construct($dataSource);
    }
    
    /**
     * This is the method to be called when setting a rule for a data field. For
     * an example, to validate field "username" with rules required, alpha, min,
     * and max, we could do it like:
     *
     * $validatorInstance->setRule('username', 'required|alpha|min:5|max:12');
     *
     * The rules are piped together and are processed in the order specified in the
     * rules string.
     * 
     * The key to validate is stored in the rules array only if it is a valid key
     * i.e. it has to be present as a key in the array of data to be validated.
     *
     * @access  public
     * @param  string  $key    The name of the data key or field to validate.
     * @param  string  $rules  The string containing rules piped together
     * @throws RuntimeException
     */
    public function setRule($key, $rules)
    {
        if(array_key_exists($key, $this->dataSource)) {
            $this->addRule($key, $rules);    
        } else {
            throw new RuntimeException(sprintf("Invalid key: %s", $key));
        }

        return $this;
    }

    /**
     * This method sets custom error message for a field.
     * 
     * @param  string  $key
     * @param  string  $message
     */
    public function setError($key, $message)
    {
        if (array_key_exists($key, $this->dataSource)) {
            $this->customErrors[$key] = $message;
        }  

        return $this;
    }

    /**
     * This method provides a way to bunch a number of rules
     * together.
     * 
     * Example:
     * 
     * $validator->setRules([
     *      'email' => 'required|email',
     *      'name' => 'required|alpha:min:3',
     * ]);
     * 
     * Or, provide a nested array for custom field label and error messages. 
     * 
     * $validator->setRules([
     *      'email' => 'required|email',
     *      'name' => [
     *           'rules' => 'required|alpha:min:3', 
     *           'label' => 'Your name', 
     *           'error'=> 'Name is required'
     *      ],
     * ]);
     */
    public function setRules(array $config)
    {
        foreach($config as $key => $rules) {
            $this->setRule($key, $rules);
        }

        return $this;
    }

    /**
     * This method provides a shortcut to bunch together
     * a number of custom errors.
     */
    public function setErrors(array $config)
    {
        foreach($config as $key => $message) {
            $this->setError($key, $message);
        }

        return $this;
    }
    
    /**
     * This is the method to be called when all the rules have been set. 
     * It simply delegates the task to a protected method inherited from 
     * the base class.
     *
     * @access  public
     */
    public function run()
    {
        $this->processRules();
        return $this;
    }
    
    /**
     * This method confirms the state of overall validation. Call this method to
     * ensure that the data source passes all the validation rules imposed.
     *
     * @access  public
     * @return  boolean  Return true if we have no validation errors else false.
     */
    public function hasErrors()
    {
        return empty($this->errors) === false;
    }
    
    /**
     * This method returns the error message associated with a data field that failed
     * the validation. In case there is no error, it simply returns an empty string.
     *
     * @access  public
     * @return  string  The error message for a field.
     */
    public function getError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : '';
    }
    
    /**
     * This method returns an array of all errors after validation.
     *
     * @access  public
     * @return  array  An array of error messages.
     */
    public function getErrors()
    {
        return $this->errors;
    }
}