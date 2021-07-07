<?php

namespace App\Services;

use Lightpack\Http\Request;
use Lightpack\Container\Container;

class RequestService implements IService
{
    public function register(Container $container)
    {
        $container->register('request', function ($container) {
            return new Request();
        });
    }
}
