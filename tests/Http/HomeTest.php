<?php

namespace Tests\Http;

use Lightpack\Testing\TestCase;

class HomeTest extends TestCase
{
    public function testItRendersHomePage()
    {
        $this->request('GET', '/');

        $this->assertResponseStatus(200);
    }

    public function testItRenders404Page()
    {
        $this->assertRouteNotFound('/does-not-exist');
    }
}
