<?php

namespace App\Providers;

use Lightpack\Container\Container;

interface ProviderInterface
{
    public function register(Container $container);
}