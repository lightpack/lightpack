<?php

namespace App\Services;

use Lightpack\Config\Config;
use Lightpack\Container\Container;

class ConfigService implements IService
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
            ]);
        });
    }
}
