<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SqliteTest extends TestCase
{
    public function testCanCreateConnectionInstance()
    {
        $config = ['database' => ':memory'];
        $connection = new \Framework\Database\Adapters\Sqlite($config);
        $this->assertInstanceOf(\Framework\Database\Pdo::class, $connection);
    }

    public function testDatabaseFileNotFoundException()
    {
        $this->expectException(\Exception::class);
        $config = ['database' => 'some-non-existent-file-path'];
        $connection = new \Framework\Database\Adapters\Sqlite($config);
    }
}
