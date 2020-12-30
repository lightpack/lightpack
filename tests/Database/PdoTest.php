<?php

use PHPUnit\Framework\TestCase;

final class PdoTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $config = require __DIR__ . '/tmp/mysql.config.php';
        $this->db = new \Framework\Database\Adapters\Mysql($config);   
    }

    public function testTableMethod()
    {
        $query = $this->db->table('products');
        $this->assertInstanceOf(\Framework\Database\Query\Query::class, $query);
    }
}