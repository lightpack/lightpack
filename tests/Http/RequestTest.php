<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    private $uri;
    private $basepath;
    private $fullpath;
    private $path;
    private $query;
    private $request;

    public function setUp(): void
    {
        $basepath = '/lightpack';
        $_SERVER['REQUEST_URI'] = $basepath . '/users/23?status=1&level=3';

        $this->uri = $_SERVER['REQUEST_URI'];
        $this->basepath = $basepath;
        $this->fullpath = explode('?', $this->uri)[0];
        $this->path = substr($this->fullpath, strlen($this->basepath));
        $this->query = explode('?', $this->uri)[1] ?? '';
        $this->request = new \Lightpack\Http\Request($this->basepath);
    }

    public function testRequestPath()
    {
        $this->assertSame(
            $this->path,
            $this->request->path(),
            "Request path should be {$this->path}"
        );
    }

    public function testRequestBasePath()
    {
        $this->assertSame(
            $this->basepath,
            $this->request->basepath(),
            "Basepath should be {$this->basepath}"
        );
    }

    public function testRequestFullPath()
    {
        $this->assertSame(
            $this->fullpath,
            $this->request->fullpath(),
            "Full request path should be {$this->fullpath}"
        );
    }

    public function testRequestUrl()
    {
        $this->assertSame(
            $this->uri,
            $this->request->uri(),
            "Request URI should be {$this->uri}"
        );
    }

    public function testRequestQueryString()
    {
        $this->assertSame(
            $this->query,
            $this->request->query(),
            "Request query should be {$this->query}"
        );
    }

    public function testRequestGetParams()
    {
        $_GET = ['name' => 'Pradeep'];

        $this->assertSame(
            $_GET['name'],
            $this->request->get('name'),
            "GET[name] should be {$_GET['name']}"
        );

        $this->assertSame(
            'Mumbai',
            $this->request->get('city', 'Mumbai'),
            'GET[city] should be Mumbai'
        );

        $this->assertSame(
            null,
            $this->request->get('foo'),
            'GET[foo] should be null'
        );

        $this->assertEquals($_GET, $this->request->get());
    }

    public function testRequestPostParams()
    {
        $_POST = ['name' => 'Pradeep'];

        $this->assertSame(
            $_POST['name'],
            $this->request->post('name'),
            "POST[name] should be {$_POST['name']}"
        );

        $this->assertSame(
            'Mumbai',
            $this->request->post('city', 'Mumbai'),
            'POST[city] should be Mumbai'
        );

        $this->assertSame(
            null,
            $this->request->post('foo'),
            'POST[foo] should be null'
        );

        $this->assertEquals($_POST, $this->request->post());
    }

    public function testRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertSame(
            'GET',
            $this->request->method(),
            'Request method should be GET'
        );
    }

    public function testRequestMethodIsGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertTrue(
            $this->request->isGet(),
            'Request method should be GET'
        );
    }

    public function testRequestMethodIsPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertTrue(
            $this->request->isPost(),
            'Request method should be POST'
        );
    }

    public function testRequestMethodIsPut()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $this->assertTrue(
            $this->request->isPut(),
            'Request method should be PUT'
        );
    }

    public function testRequestMethodIsPatch()
    {
        $_SERVER['REQUEST_METHOD'] = 'PATCH';

        $this->assertTrue(
            $this->request->isPatch(),
            'Request method should be PATCH'
        );
    }

    public function testRequestMethodIsDelete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $this->assertTrue(
            $this->request->isDelete(),
            'Request method should be DELETE'
        );
    }

    public function testRequestIsValid()
    {
        foreach($this->request->verbs() as $verb) {
            $_SERVER['REQUEST_METHOD'] = $verb;
            $this->assertTrue($this->request->isValid());
        }

        $_SERVER['REQUEST_METHOD'] = 'GETPOST';
        $this->assertFalse($this->request->isValid());
    }

    public function testRequestIsAjax()
    {
        $_SERVER['X-Requested-With']= 'XMLHttpRequest';
        $this->assertTrue($this->request->isAjax());
    }

    public function testRequestIsJson()
    {
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $this->assertTrue($this->request->isJson());

        $_SERVER['CONTENT_TYPE'] = 'application/xml';
        $this->assertFalse($this->request->isJson());
    }

    public function testRequestIsSecure()
    {
        $_SERVER['HTTPS'] = 'on';
        $this->assertTrue($this->request->isSecure());
    }

    public function testRequestScheme()
    {
        $_SERVER['HTTPS'] = 'off';
        $this->assertEquals('http', $this->request->scheme());
    }

    public function testRequestHost()
    {
        $_SERVER['HTTP_HOST'] = 'example.com';
        $this->assertEquals('example.com', $this->request->host());
    }

    public function testRequestProtocol()
    {
        $this->assertEquals('HTTP/1.1', $this->request->protocol());
    }

    public function testRequestSegments()
    {
        $this->assertEquals(['users', 23], $this->request->segments());
        $this->assertEquals('users', $this->request->segments(0));
        $this->assertEquals(23, $this->request->segments(1));
    }
}
