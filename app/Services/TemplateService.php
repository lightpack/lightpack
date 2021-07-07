<?php

namespace App\Services;

use Lightpack\View\Template;
use Lightpack\Container\Container;

class TemplateService implements IService
{
    public function register(Container $container)
    {
        $container->register('template', function ($container) {
            return new Template();
        });
    }
}
