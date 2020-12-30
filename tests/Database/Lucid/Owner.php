<?php

require_once 'Product.php';

use \Framework\Database\Lucid\Model;

class Owner extends Model
{   
    public function __construct()
    {
        parent::__construct('owners');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}