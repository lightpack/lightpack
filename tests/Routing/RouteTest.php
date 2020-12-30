<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    private const HTTP_GET = 'GET';
    private const HTTP_POST = 'POST';
    private const HTTP_PUT = 'PUT';
    private const HTTP_PATCH = 'PATCH';
    private const HTTP_DELETE = 'DELETE';
    private $verbs = [self::HTTP_GET, self::HTTP_POST, self::HTTP_PUT, self::HTTP_PATCH, self::HTTP_DELETE];
    private $route;

    public function setUp(): void
    {
        $request = new \Framework\Http\Request();
        $this->route = new \Framework\Routing\Route($request);
    }

    public function testRoutePathException()
    {
        $this->expectException('\\Exception');
        $this->route->get('', 'UserController@index');
        $this->fail('Empty route path');
    }

    public function testRouteMetaException()
    {
        $this->expectException('\\Exception');
        $this->route->get('/users', 'UserController:index');
        $this->fail('Invalid route path configuration');
    }

    public function testRoutePathsRegisteredForMethod()
    {
        $this->route->get('/users', 'UserController@index');
        $this->route->get('/users/23', 'UserController@edit');
        $this->route->post('/users/23', 'UserController@delete');
        $this->route->put('/users', 'UserController@index');
        $this->route->patch('/users', 'UserController@index');
        $this->route->delete('/users', 'UserController@index');

        $this->assertCount(
            2,
            $this->route->paths(self::HTTP_GET),
            "Route should have registered 2 paths for GET"
        );

        $this->assertCount(
            1,
            $this->route->paths(self::HTTP_POST),
            "Route should have registered 1 path for POST"
        );

        $this->assertCount(
            1,
            $this->route->paths(self::HTTP_PUT),
            "Route should have registered 1 path for PUT"
        );

        $this->assertCount(
            1,
            $this->route->paths(self::HTTP_PATCH),
            "Route should have registered 1 path for PATCH"
        );

        $this->assertCount(
            1,
            $this->route->paths(self::HTTP_DELETE),
            "Route should have registered 1 path for DELETE"
        );
    }

    public function testRouteMatchesUrl()
    {
        $this->route->get('/users', 'UserController@index');
        $this->route->get('/users/:num/role/:alpha', 'UserController@showForm');
        $this->route->post('/users/:num/profile', 'UserController@submitForm');

        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->assertTrue(
            $this->route->matches('/users'),
            'Route should match request GET /users'
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->assertTrue(
            $this->route->matches('/users/23/role/editor'),
            'Route should match request GET /users/23/role/editor'
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_POST;
        $this->assertTrue(
            $this->route->matches('/users/23/profile'),
            'Route should match request POST /users/23/profile'
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_POST;
        $this->assertTrue(
            $this->route->matches('/users/23/profile'),
            'Route should match request POST /users/23/profile'
        );
    }

    public function testRouteShouldMapMultipleVerbs() {
        $this->route->map(['GET', 'POST'], '/users/:num', 'UserController@index');

        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->assertTrue(
            $this->route->matches('/users/23'),
            'Route should match request GET /users/23'
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_POST;
        $this->assertTrue(
            $this->route->matches('/users/23'),
            'Route should match request POST /users/23'
        );

        $_SERVER['REQUEST_METHOD'] = self::HTTP_PUT;
        $this->assertFalse(
            $this->route->matches('/users/23'),
            'Route should not match request PUT /users/23'
        );

        $this->expectException('\\Exception');
        $this->route->map(['get'], '/users', 'UserController@index');
        $this->fail('Unsupported HTTP request method: ' . 'get');
    }

    public function testRouteShouldMapAnyAllowedVerb() {
        $this->route->any('/users/:num', 'UserController@index');

        foreach($this->verbs as $verb) {
            $_SERVER['REQUEST_METHOD'] = $verb;
            $this->assertTrue(
                $this->route->matches('/users/23'),
                "Route should match {$verb} request"
            );
        }
    }

    public function testRouteMatchesGroupedUrls() {
        $this->route->group(
            ['prefix' => '/admin'], 
            function($route) {
                $route->get('/users/:num', 'UserController@index');
                $route->post('/users/:num', 'UserController@index');
                $route->put('/users/:num', 'UserController@index');
                $route->patch('/users/:num', 'UserController@index');
                $route->delete('/users/:num', 'UserController@index');
                $route->any('/pages/:num', 'PageController@index');
            }
        );

        foreach($this->verbs as $verb) {
            $_SERVER['REQUEST_METHOD'] = $verb;
            $this->assertTrue(
                $this->route->matches('/admin/users/23'),
                "Route should match group request {$verb} /admin/users/23"
            );
            $this->assertTrue(
                $this->route->matches('/admin/pages/23'),
                "Route should match group request {$verb} /admin/pages/23"
            );
        }
    }

    public function testRouteShouldResetScopeToDefault() {
        $this->route->group(['prefix' => '/admin'], function($route) {
            $route->get('/users/:num', 'UserController@index');
        });

        // new routes should reset scope from '/admin' to default
        $this->route->get('/users/:num', 'UserController@index');

        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->assertTrue(
            $this->route->matches('/users/23'),
            'Route should reset scope to match request GET /users/23'
        );
    }

    public function testRouteShouldMatchNestedGroupUrls() {
        // nested prefix
        $this->route->group(['prefix' => '/admin'], function($route) {
            $route->group(['prefix' => '/posts'], function($route) {
                $route->get('/edit/:num', 'PostController@edit');
            });
            $route->group(['prefix' => '/dashboard'], function($route) {
                $route->group(['prefix' => '/charts'], function($route) {
                    $route->get('/:num', 'ChartController@index');
                });
            });
        });
        // non prefix
        $this->route->get('/users/:num/profile', 'UserController@profile');

        // assertions
        $_SERVER['REQUEST_METHOD'] = self::HTTP_GET;
        $this->assertTrue(
            $this->route->matches('/admin/posts/edit/23'),
            'Route should match nested group url: /admin/posts/edit/23'
        );
        $this->assertTrue(
            $this->route->matches('/admin/dashboard/charts/23'),
            'Route should match nested group url: /admin/dashboard/charts/23'
        );
        $this->assertTrue(
            $this->route->matches('/users/23/profile'),
            'Route should match non-nested url: /users/23/profile'
        );
    }
}