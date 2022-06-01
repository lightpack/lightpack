<?php

namespace Tests;

use Lightpack\Testing\Http\TestCase;

class HttpTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        require __DIR__ . '/../bootstrap/init.php';

        $this->container = $GLOBALS['container'];
    }
}
