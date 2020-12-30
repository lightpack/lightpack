<?php

namespace Framework\Database\Adapters;

use Framework\Database\Pdo;

class Sqlite extends Pdo
{
    public function __construct(array $args)
    {
        if ($args['database'] !== ':memory' && !file_exists($args['database'])) {
            throw new \Exception("Could not locate Sqlite database file:{$args['database']}");
		}
        
        $dsn = 'sqlite:' . $args['database'] . ':';
        parent::__construct($dsn);
    }
}