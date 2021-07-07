<?php

namespace App\Providers;

use Lightpack\Module\Module;
use Lightpack\Container\Container;

class ModuleProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('module', function ($container) {
            return new Module(
                $container->get('request'),
                $container->get('config')
            );
        });
    }
}
