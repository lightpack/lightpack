<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    private $event;

    public function setUp(): void
    {
        $this->container = new \Framework\Container\Container();
        $this->event = new \Framework\Event\Event();
    }
    public function testSubscribeMethod()
    {
        $this->event->subscribe('event1', 'EventHandler1');
        $this->event->subscribe('event1', 'EventHandler2');
        $this->event->subscribe('event2', 'EventHandler3');
        $this->event->subscribe('event2', 'EventHandler4');

        $reflection = new \ReflectionClass($this->event);
        $reflectionSubscribers = $reflection->getProperty('subscribers');
        $reflectionSubscribers->setAccessible(true);

        $this->assertEquals(
            [
                'event1'  => ['EventHandler1', 'EventHandler2'],
                'event2'  => ['EventHandler3', 'EventHandler4'],
            ], 
            $reflectionSubscribers->getValue($this->event)
        );
    }
    public function testUnsubscribeMethod()
    {
        $this->event->subscribe('event1', 'EventHandler1');
        $this->event->subscribe('event1', 'EventHandler2');
        $this->event->subscribe('event2', 'EventHandler3');
        $this->event->subscribe('event2', 'EventHandler4');
        $this->event->subscribe('event2', 'EventHandler2');

        $this->event->unsubscribe('EventHandler2');
        $this->event->unsubscribe('EventHandler4');

        $reflection = new ReflectionClass($this->event);
        $reflectionSubscribers = $reflection->getProperty('subscribers');
        $reflectionSubscribers->setAccessible(true);

        $this->assertEquals(
            [
                'event1'  => ['EventHandler1'],
                'event2'  => ['EventHandler3'],
            ], 
            $reflectionSubscribers->getValue($this->event)
        );
    }
    public function testEventNotFoundException()
    {
        $this->expectException(\Framework\Exceptions\EventNotFoundException::class);
        $this->event->notify('event');
    }
    public function testEventHandlerMethodNotFoundException()
    {
        $mockEvent = $this->getMockBuilder(\stdClass::class)->getMock();
        $this->event->subscribe('event', get_class($mockEvent));
        $this->expectException(\Framework\Exceptions\EventHandlerMethodNotFoundException::class);
        $this->event->notify('event');
    }
    public function testEventSubscribersMethod()
    {
        $this->event->subscribe('event', 'EventHandler1');
        $this->event->subscribe('event', 'EventHandler2');
        $this->assertEquals(['event' => ['EventHandler1', 'EventHandler2']], $this->event->getSubscribers());
    }
    public function testEventDataGetMethod()
    {
        $eventData = ['id' => 23];
        $this->event->setData($eventData);
        $this->assertEquals(['id' => 23], $this->event->getData());
    }
}