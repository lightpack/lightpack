<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SqliteTest extends TestCase
{
    public function testCanCreateConnectionInstance()
    {
        $config = ['database' => ':memory'];
        $connection = new \Lightpack\Database\Adapters\Sqlite($config);
        $this->assertInstanceOf(\Lightpack\Database\Pdo::class, $connection);
    }

    public function testDatabaseFileNotFoundException()
    {
        $this->expectException(\Exception::class);
        $config = ['database' => 'some-non-existent-file-path'];
        $connection = new \Lightpack\Database\Adapters\Sqlite($config);
    }
}
