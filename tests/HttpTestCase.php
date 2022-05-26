<?php

namespace Tests;

use Lightpack\Testing\HttpTestCase as BaseHttpTestCase;

class HttpTestCase extends BaseHttpTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        require __DIR__ . '/../bootstrap/init.php';

        $this->container = $GLOBALS['container'];
    }
}
