<?php

namespace App\Providers;

use Lightpack\Cache\Cache;
use Lightpack\Cache\Drivers\File;
use Lightpack\Container\Container;

class CacheProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('cache', function ($container) {
            $cacheDir = $container->get('config')->get('storage.cache');
            $fileStorage = new File($cacheDir);
        
            return new Cache($fileStorage);
        });
    }
}
