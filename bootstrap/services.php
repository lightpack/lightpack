<?php

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Lightpack\Container\Container();

/**
 * ------------------------------------------------------------
 * Register Configuration Service Provider.
 * ------------------------------------------------------------
 */

$container->register('config', function ($container) {
    return new Lightpack\Config\Config([
        'app', 
        'log',
        'modules',
        'filters', 
        'cors'
    ]);
});

/**
 * ------------------------------------------------------------
 * Register Global Event Handler.
 * ------------------------------------------------------------
 */

$container->register('event', function ($container) {
    return new Lightpack\Event\Event();
});

/**
 * ------------------------------------------------------------
 * Register App Filter Service Provider.
 * ------------------------------------------------------------
 */

$container->register('filter', function ($container) {
    return new Lightpack\Filters\Filter(
        $container->get('request'),
        $container->get('response')
    );
});

/**
 * ------------------------------------------------------------
 * Register Incoming HTTP Request.
 * ------------------------------------------------------------
 */

$container->register('request', function ($container) {
    return new Lightpack\Http\Request();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Response.
 * ------------------------------------------------------------
 */

$container->register('response', function ($container) {
    return new Lightpack\Http\Response();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Cookie.
 * ------------------------------------------------------------
 */

$container->register('cookie', function ($container) {
    return new Lightpack\Http\Cookie(
        $container->get('config')->cookie_secret
    );
});

/**
 * ------------------------------------------------------------
 * Register App Session Handler.
 * ------------------------------------------------------------
 */

$container->register('session', function ($container) {
    $sessionName = get_env('SESSION_NAME', 'lightpack');

    return new Lightpack\Http\Session($sessionName);
});

/**
 * ------------------------------------------------------------
 * Register Templating Service Provider.
 * ------------------------------------------------------------
 */

$container->register('template', function ($container) {
    return new Lightpack\View\Template();
});

/**
 * ------------------------------------------------------------
 * Register Modules Service Provider.
 * ------------------------------------------------------------
 */

$container->register('module', function ($container) {
    return new Lightpack\Module\Module(
        $container->get('request'),
        $container->get('config')
    );
});

/**
 * ------------------------------------------------------------
 * Register Application Route Manager.
 * ------------------------------------------------------------
 */

$container->register('route', function ($container) {
    return new Lightpack\Routing\Route(
        $container->get('request')
    );
});

/**
 * ------------------------------------------------------------
 * Register URL Router Service.
 * ------------------------------------------------------------
 */

$container->register('router', function ($container) {
    return new Lightpack\Routing\Router(
        $container->get('request'),
        $container->get('route')
    );
});

/**
 * ------------------------------------------------------------
 * Register MySQL Database Service Manager.
 * ------------------------------------------------------------
 */

$container->register('db', function ($container) {
    return new Lightpack\Database\Adapters\MySql(
        $container->get('config')->default['connection']['mysql']
    );
});

/**
 * ------------------------------------------------------------
 * Register Cache Service Provider.
 * ------------------------------------------------------------
 */

$container->register('cache', function ($container) {
    $cacheDir = $container->get('config')->default['cache']['storage'];
    $fileStorage = new Lightpack\Cache\Drivers\File($cacheDir);

    return new Lightpack\Cache\Cache($fileStorage);
});

/**
 * ------------------------------------------------------------
 * Register log service provider.
 * ------------------------------------------------------------
 */

$container->register('logger', function ($container) {
    $logDriver = new Lightpack\Logger\Drivers\NullLogger;

    if ('file' === get_env('LOG_DRIVER')) {
        $logDriver = new Lightpack\Logger\Drivers\FileLogger(
            $container->get('config')->get('log.filename')
        );
    }

    return new Lightpack\Logger\Logger($logDriver);
});
