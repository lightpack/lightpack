<?php

namespace App\Services;

use Lightpack\Http\Response;
use Lightpack\Container\Container;

class ResponseService implements IService
{
    public function register(Container $container)
    {
        $container->register('response', function ($container) {
            return new Response();
        });
    }
}
