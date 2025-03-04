<?php

namespace Tests;

use Lightpack\Container\Container;
use Lightpack\Testing\Http\TestCase;

class HttpTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require __DIR__ . '/../bootstrap/init.php';

        $this->container = Container::getInstance();
    }
}
