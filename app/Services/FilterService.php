<?php

namespace App\Services;

use Lightpack\Http\Response;
use Lightpack\Container\Container;
use Lightpack\Filters\Filter;

class FilterService implements IService
{
    public function register(Container $container)
    {
        $container->register('filter', function ($container) {
            return new Filter(
                $container->get('request'),
                $container->get('response')
            );
        });
    }
}
