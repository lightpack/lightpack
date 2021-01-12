<?php

namespace Lightpack\Exceptions;

class RecordNotFoundException extends \Exception 
{
    public function __construct() 
    {
        parent::__construct('Requested record/entity does not exists.', 404);
    }
}