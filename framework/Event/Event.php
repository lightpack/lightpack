<?php

namespace Framework\Event;

class Event
{
    private $subscribers = [];
    private $data;

    public function subscribe(string $eventName, string $eventSubscriber): void
    {
        $this->subscribers[$eventName][] = $eventSubscriber;
    }
    public function unsubscribe(string $eventSubscriber): void
    {
        $eventNames = array_keys($this->subscribers);

        foreach ($eventNames as $eventName) {
            $key = array_search($eventSubscriber, $this->subscribers[$eventName]);

            if ($key !== false) {
                unset($this->subscribers[$eventName][$key]);
            }
        }
    }
    public function notify(string $eventName)
    {
        $this->throwExceptionIfEventNotFound($eventName);
        
        foreach ($this->subscribers[$eventName] as $eventSubscriber) {
            $subscriberInstance = new $eventSubscriber;
            $this->throwExceptionIfHandlerMethodNotFound($eventSubscriber, $subscriberInstance);
            $subscriberInstance->handle();
        }
    }
    public function getSubscribers()
    {
        return $this->subscribers;
    }
    public function setData($data = null)
    {
        $this->data = $data;
        return $this;
    }
    public function getData()
    {
        return $this->data;
    }
    private function throwExceptionIfEventNotFound(string $eventName): void
    {
        if (!isset($this->subscribers[$eventName])) {
            throw new \Framework\Exceptions\EventNotFoundException(
                sprintf(
                    'Event `%s` is not registered',
                    $eventName
                )
            );
        }
    }
    private function throwExceptionIfHandlerMethodNotFound(string $subscriber, object $instance): void
    {
        if(!method_exists($instance, 'handle')) {
            throw new \Framework\Exceptions\EventHandlerMethodNotFoundException(
                sprintf(
                    'Event subscriber `%s` has not implemented handle() method.',
                    $subscriber
                )
            );
        }
    }
}
