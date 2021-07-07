<?php

namespace App\Services;

use Lightpack\Container\Container;

interface IService
{
    public function register(Container $container);
}