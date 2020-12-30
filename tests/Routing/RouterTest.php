<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    private const HTTP_GET = 'GET';

    public function setUp(): void
    {
        $basepath = '/lightpack';
        $_SERVER['REQUEST_URI'] = $basepath . '/users/23';

        $request = new \Framework\Http\Request($basepath);
        $this->route = new \Framework\Routing\Route($request);
        $this->router = new \Framework\Routing\Router($request, $this->route);
    }

    public function testRouterCanParseUrl()
    {
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->route->get('/users/:num/role/:alpha/:any', 'UserController@index');
        $this->router->parse('/users/23/role/admin/hello/world');

        $this->assertEquals(
            'UserController',
            $this->router->controller(),
            'Router should parse controller: UserController'
        );

        $this->assertEquals(
            'index',
            $this->router->action(),
            'Router should parse action: index'
        );

        $this->assertSame(
            ['23', 'admin', 'hello/world'],
            $this->router->params(),
            'Router should parse params correctly'
        );
    }

    public function testRouterCanParseMultipleUrls()
    {
        // 'path' => ['method', 'route', 'Controller@action', $routeMeta]
        // example: '/news/23' => ['GET', 'route', 'News@index', ['controller' => 'News', 'action' => 'index', 'path' => '/news/23', 'route' => '/news/:num', 'params' => ['23']]]
        $routes = [
            '/' => ['GET', '/', 'News@handle', []],
            '/news' => ['GET', '/news', 'News@handle', []],
            '/news/order/asc' => ['POST', '/news/order/:alpha', 'News@handle', ['asc']],
            '/news/23/category/politics' => ['PUT', '/news/:num/category/:slug', 'News@handle', ['23', 'politics']],
            '/news/v2.0/latest/politics' => ['PATCH', '/news/:seg/:seg/:alpha', 'News@handle', ['v2.0', 'latest', 'politics']],
            '/news/author/bob-walter/id-23' => ['DELETE', '/news/:alpha/:any', 'News@handle', ['author', 'bob-walter/id-23']],
            '/news/way2go/id-23' => ['GET', '/news/:alnum/:any', 'News@handle', ['way2go', 'id-23']],
        ];

        foreach($routes as $path => $config) {
            // Prepare data
            $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
            $method = $config[0];
            $route = $config[1];
            $controllerAction = $config[2];
            $controller = explode('@', $config[2])[0];
            $action = explode('@', $config[2])[1];
            $params = $config[3];
            $method = self::HTTP_GET;
            $meta = $config[3];

            // Initialize setup
            $this->route->{$method}($route, $controllerAction);
            $this->router->parse($path);

            // Assertions
            $this->assertEquals($path, $this->router->path(), "Router should parse path: {$path}");
            $this->assertEquals($route, $this->router->route(), "Router should parse route: {$route}");
            $this->assertEquals($controller, $this->router->controller(), "Router should parse controller: {$controller}");
            $this->assertEquals($action, $this->router->action(), "Router should parse action: {$action}");
            $this->assertEquals($params, $this->router->params(), "Router should parse params correctly");
            $this->assertEquals($method, $this->router->method(), "Router should parse method: {$method}");
        }
    }

    public function testRouterCanParseUrlMeta()
    {
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->route->get('/news/:num/author/:slug', 'News@index', ['auth', 'csrf']);
        $this->router->parse('/news/23/author/bob');
        
        $actual = $this->router->meta();
        $meta = [
            'method' => 'GET',
            'controller' => 'News',
            'action' => 'index',
            'route' => '/news/:num/author/:slug',
            'path' => '/news/23/author/bob',
            'params' => ['23', 'bob'],
            'filters' => ['auth', 'csrf']
        ];

        foreach($meta as $key => $value) {
            $this->assertTrue(isset($actual[$key]), "Router should have parsed meta key: {$key}");
            $this->assertEquals($value, $actual[$key], "Router should have parsed correctly meta value for key: {$key}");
        }
    }

    public function testRouterCanParseBadUrlMeta()
    {
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->route->get('/news/:slug', 'News@index');
        $this->router->parse('/news//23');
        
        // should be []
        $this->assertEmpty($this->router->meta(), "Router should have an empty route meta for bad url requests");
    }

    public function testRouterCanParseRegexUrl()
    {
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->route->get('/news/([0-9]+)/slug/([a-zA-Z]+)', 'News@index');
        $this->router->parse('/news/23/slug/politics');

        $this->assertEquals(['23', 'politics'], $this->router->params());
    }

    public function testRouterCanParseComplexUrl()
    {
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->route->get('/news/id-([0-9]+)/slug/political-([a-zA-Z]+)', 'News@index');
        $this->router->parse('/news/id-23/slug/political-agenda');
        $this->assertEquals(['23', 'agenda'], $this->router->params());
    }

    public function testRouterCanParseGroupOptions() {
        $this->route->group(
            [
                'prefix' => '/admin', 
                'namespace' => 'App\Controllers\Admin', 
                'filters' => ['auth', 'csrf']
            ], 
            function($route) {
                $route->get('/users/:num', 'UserController@index');
            }
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->router->parse('/admin/users/23');

        // tests
        $this->assertEquals('App\Controllers\Admin', $this->router->namespace());
        $this->assertEquals(['auth', 'csrf'], $this->router->filters());
    }
}
