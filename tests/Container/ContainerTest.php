<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function setUp(): void
    {
        $this->container = new Lightpack\Container\Container();
    }
    public function testContainerHasMethod()
    {
        $this->container->register('service', function() { return new stdClass(); });
        $this->assertTrue($this->container->has('service'));
        $this->assertFalse($this->container->has('mailer'));
    }
    public function testContainerGetMethod()
    {
        $this->container->register('service', function() { return new stdClass(); });
        $this->assertInstanceOf(stdClass::class, $this->container->get('service'));
        $this->assertSame($this->container->get('service'), $this->container->get('service'));
        $this->expectException(\Lightpack\Exceptions\ServiceNotFoundException::class);
        $this->container->get('mailer');
    }
    public function testContainerFactoryMethod()
    {
        $this->container->factory('service', function() { return new stdClass(); });
        $this->assertNotSame($this->container->get('service'), $this->container->get('service'));
        $this->assertInstanceOf(stdClass::class, $this->container->get('service'));
    }
}
