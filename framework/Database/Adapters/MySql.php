<?php

namespace Lightpack\Database\Adapters;

use Lightpack\Database\Pdo;

class MySql extends Pdo
{
    public function __construct(array $args)
    {
        $dsn = "mysql:host={$args['host']};port={$args['port']};dbname={$args['database']}";
        parent::__construct($dsn, $args['username'], $args['password'], $args['options']);
    }
}