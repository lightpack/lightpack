<?php

namespace App\Providers;

use Lightpack\Http\Response;
use Lightpack\Container\Container;

class ResponseProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('response', function ($container) {
            return new Response();
        });
    }
}
