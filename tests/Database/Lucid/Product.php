<?php

require_once 'Owner.php';
require_once 'Option.php';

use \Framework\Database\Lucid\Model;

class Product extends Model
{   
    public function __construct()
    {
        parent::__construct('products');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'product_id');
    }

    public function owner()
    {
        return $this->hasOne(Owner::class, 'product_id');
    }
}