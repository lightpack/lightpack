<?php

use PHPUnit\Framework\TestCase;

require 'MockFilter.php';

final class FilterTest extends TestCase
{
    private $filter;

    public function setUp(): void
    {
        $this->request = new \Lightpack\Http\Request();
        $this->response = new \Lightpack\Http\Response();
        $this->filter = new \Lightpack\Filters\Filter($this->request, $this->response);
        $this->mockFilter = new MockFilter();
    }

    public function testFilterBeforeMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $this->filter->register('/users', $this->mockFilter);
        $result = $this->filter->processBeforeFilters('/users');
        $this->assertTrue($this->request->post('framework') == 'Lightpack');
    }

    public function testFilterBeforeMethodReturnsResponse()
    {
        $_SERVER['REQUEST_METHOD'] = 'get';
        $this->filter->register('/users', $this->mockFilter);
        $result = $this->filter->processBeforeFilters('/users');
        $this->assertInstanceOf(\Lightpack\Http\Response::class, $result);
    }

    public function testFilterAfterMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'get';
        $this->filter->register('/users', $this->mockFilter);
        $result = $this->filter->processAfterFilters('/users');
        $this->assertInstanceOf(\Lightpack\Http\Response::class, $result);
        $this->assertTrue($result->getBody() == 'hello');
    }
    
    public function __testFilterBeforeMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $request = new \Lightpack\Http\Request();
        $this->mockFilter->before($request);
        $this->assertTrue($request->post('framework') == 'Lightpack');
    }
}