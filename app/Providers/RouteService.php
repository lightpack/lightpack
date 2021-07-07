<?php

namespace App\Providers;

use Lightpack\Routing\Route;
use Lightpack\Container\Container;

class RouteProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('route', function ($container) {
            return new Route(
                $container->get('request')
            );
        });
    }
}
