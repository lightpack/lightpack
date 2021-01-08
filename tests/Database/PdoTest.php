<?php

use PHPUnit\Framework\TestCase;

final class PdoTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $config = require __DIR__ . '/tmp/mysql.config.php';
        $this->db = new \Lightpack\Database\Adapters\Mysql($config);   
    }

    public function testTableMethod()
    {
        $query = $this->db->table('products');
        $this->assertInstanceOf(\Lightpack\Database\Query\Query::class, $query);
    }
}