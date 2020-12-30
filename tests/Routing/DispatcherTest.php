<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DispatcherTest extends TestCase
{
    private $route;
    private $router;

    public function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->request = new \Framework\Http\Request('/lightpack');
        $this->route = new \Framework\Routing\Route($this->request);
        $this->router = new \Framework\Routing\Router($this->request, $this->route);
    }

    public function testRouteNotFoundException() {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // assertions
        $this->expectException('\\Framework\\Exceptions\\RouteNotFoundException');
        new \Framework\Routing\Dispatcher($this->request, $this->router);
        $this->fail('404: Route not found exception');
    }

    public function testControllerNotFoundException() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->route->get('/users/:num', 'UserController@index');
        $this->router->parse('/users/23');
        $dispatcher = new \Framework\Routing\Dispatcher($this->request, $this->router);

        // assertions
        $this->expectException('\\Framework\\Exceptions\\ControllerNotFoundException');
        $dispatcher->dispatch();
        $this->fail('404: Controller not found exception');
    }

    public function testActionNotFoundException() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $controller = $this->getMockBuilder('UserController')->getMock();
        $this->route->get('/users/:num', get_class($controller) . '@index');
        $this->router->parse('/users/23');
        $dispatcher = new \Framework\Routing\Dispatcher($this->request, $this->router);

        // assertions
        $this->expectException('\\Framework\\Exceptions\\ActionNotFoundException');
        $dispatcher->dispatch();
        $this->fail('404: Action not found exception');
    }

    public function testControllerActionInvocation() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/lightpack/hello';

        $this->route->get('/hello', 'MockController@greet');
        $this->router->parse('/hello');
        $dispatcher = new \Framework\Routing\Dispatcher($this->request, $this->router);
        $this->assertEquals('hello', $dispatcher->dispatch());
    }
}

class MockController
{
    public function greet()
    {
        return 'hello';
    }
}