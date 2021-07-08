<?php

namespace App\Providers;

use Lightpack\Http\Cookie;
use Lightpack\Container\Container;

class CookieProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('cookie', function ($container) {
            return new Cookie(
                $container->get('config')->get('cookie.secret')
            );
        });
    }
}
