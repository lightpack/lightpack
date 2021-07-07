<?php

namespace App\Services;

use Lightpack\Module\Module;
use Lightpack\Container\Container;

class ModuleService implements IService
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
