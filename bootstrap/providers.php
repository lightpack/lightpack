<?php

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Lightpack\Container\Container();

/**
 * ------------------------------------------------------------
 * List service providers here.
 * ------------------------------------------------------------
 */

$providers = [
    Lightpack\Providers\LogProvider::class,
    Lightpack\Providers\RouteProvider::class,
    Lightpack\Providers\EventProvider::class,
    Lightpack\Providers\CacheProvider::class,
    Lightpack\Providers\ConfigProvider::class,
    Lightpack\Providers\RouterProvider::class,
    Lightpack\Providers\FilterProvider::class,
    Lightpack\Providers\CookieProvider::class,
    Lightpack\Providers\SessionProvider::class,
    Lightpack\Providers\RequestProvider::class,
    Lightpack\Providers\ResponseProvider::class,
    Lightpack\Providers\DatabaseProvider::class,
    Lightpack\Providers\TemplateProvider::class,
];

/**
 * ------------------------------------------------------------
 * Bind service providers in container.
 * ------------------------------------------------------------
 */

foreach ($providers as $provider) {
    (new $provider)->register($container);
}
