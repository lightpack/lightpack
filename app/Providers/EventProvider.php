<?php

namespace App\Providers;

use Lightpack\Event\Event;
use Lightpack\Container\Container;

class EventProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('event', function ($container) {
            return new Event();
        });
    }
}
