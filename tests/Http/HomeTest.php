<?php

namespace Tests\Http;

use Tests\HttpTestCase;

class HomeTest extends HttpTestCase
{
    public function testItRendersHomePage()
    {
        $response = $this->request('GET', '/');

        $this->assertEquals(200, $response->getCode());
    }

    public function testItRenders404Page()
    {
        $this->expectException(\Lightpack\Exceptions\RouteNotFoundException::class);
        $this->request('GET', '/does-not-exist');
    }
}
