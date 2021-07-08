<?php

namespace App\Providers;

use Lightpack\Http\Session;
use Lightpack\Container\Container;

class SessionProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('session', function ($container) {
            $sessionName = get_env('SESSION_NAME', 'lightpack');
        
            return new Session($sessionName);
        });
    }
}
