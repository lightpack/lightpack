<?php

namespace App\Services;

use Lightpack\Routing\Route;
use Lightpack\Container\Container;

class RouteService implements IService
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
