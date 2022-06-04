<?php

namespace Tests\Http;

use Tests\HttpTestCase;

class HomeTest extends HttpTestCase
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
