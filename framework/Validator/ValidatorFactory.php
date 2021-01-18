<?php

namespace Lightpack\Validator;

use Reflection;
use ReflectionClass;
use RuntimeException;
use ReflectionException;

/**
 * A factory for generating strategy classes within Lightpack\Validator\Strategies
 * folder.
 *
 * Method Prototypes:
 *
 * void    public   function   __construct(string $strategy);
 * object  public   function   getStrategy(void);
 */
class ValidatorFactory
{
    /**
     * Holds an instance of strategy class.
     *
     * @var object
     */
    private $_strategyInstance = null;

    /**
     * Class constructor.
     *
     * @access  public
     * @param   string  $strategy  The name of strategy to be produced.
     * @throws  RuntimeException
     * @todo    Cache reflected objects for future references
     */
    public function __construct($strategy)
    {
        try {
            $reflectStrategy = new ReflectionClass("Lightpack\Validator\Strategies\\$strategy");
        } catch(ReflectionException $e) {
            throw new RuntimeException(sprintf("No class exists for rule: %s", $strategy));
        }

        if(!$reflectStrategy->implementsInterface('Lightpack\Validator\IValidationStrategy')) {
            throw new RuntimeException(sprintf("The class defined for rule: %s must implement interface: IValidationStrategy", $strategy));
        }

        // things are fine, let us produce our strategy instance
        $this->_strategyInstance = $reflectStrategy->newInstance();
    }

    /**
     * This method is called to get the instance.
     *
     * @access  public
     * @return  object  The strategy object.
     */
    public function getStrategy()
    {
        return $this->_strategyInstance;
    }
}
