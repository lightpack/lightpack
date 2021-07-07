<?php

namespace App\Services;

use Lightpack\Event\Event;
use Lightpack\Container\Container;

class EventService implements IService
{
    public function register(Container $container)
    {
        $container->register('event', function ($container) {
            return new Event();
        });
    }
}
