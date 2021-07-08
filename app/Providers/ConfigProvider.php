<?php

namespace App\Providers;

use Lightpack\Config\Config;
use Lightpack\Container\Container;

class ConfigProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('config', function ($container) {
            return new Config([
                'app',
                'log',
                'modules',
                'filters',
                'cors',
                'db',
                'events',
                'cookie',
                'cache',
            ]);
        });
    }
}
