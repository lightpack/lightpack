<?php

namespace App\Services;

use Lightpack\Routing\Router;
use Lightpack\Container\Container;

class RouterService implements IService
{
    public function register(Container $container)
    {
        $container->register('router', function ($container) {
            return new Router(
                $container->get('request'),
                $container->get('route')
            );
        });
    }
}
