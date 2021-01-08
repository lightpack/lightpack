<?php

use \Lightpack\Database\Lucid\Model;

class Option extends Model
{   
    public function __construct()
    {
        parent::__construct('options');
    }
}