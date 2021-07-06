<?php

use Lightpack\Cache\Cache;
use Lightpack\Event\Event;
use Lightpack\Http\Cookie;
use Lightpack\Http\Session;
use Lightpack\Http\Request;
use Lightpack\Http\Response;
use Lightpack\Module\Module;
use Lightpack\Routing\Route;
use Lightpack\View\Template;
use Lightpack\Config\Config;
use Lightpack\Logger\Logger;
use Lightpack\Filters\Filter;
use Lightpack\Routing\Router;
use Lightpack\Container\Container;
use Lightpack\Database\Adapters\Mysql;
use Lightpack\Database\Adapters\Sqlite;
use Lightpack\Logger\Drivers\FileLogger;
use Lightpack\Logger\Drivers\NullLogger;
use Lightpack\Cache\Drivers\File as FileDriver;

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Container();

/**
 * ------------------------------------------------------------
 * Register Configuration Service Provider.
 * ------------------------------------------------------------
 */

$container->register('config', function ($container) {
    return new Config([
        'app',
        'log',
        'modules',
        'filters',
        'cors',
        'db',
        'events',
        'cookie',
    ]);
});

/**
 * ------------------------------------------------------------
 * Register Global Event Handler.
 * ------------------------------------------------------------
 */

$container->register('event', function ($container) {
    return new Event();
});

/**
 * ------------------------------------------------------------
 * Register App Filter Service Provider.
 * ------------------------------------------------------------
 */

$container->register('filter', function ($container) {
    return new Filter(
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
    return new Request();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Response.
 * ------------------------------------------------------------
 */

$container->register('response', function ($container) {
    return new Response();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Cookie.
 * ------------------------------------------------------------
 */

$container->register('cookie', function ($container) {
    return new Cookie(
        $container->get('config')->get('cookie.secret')
    );
});

/**
 * ------------------------------------------------------------
 * Register App Session Handler.
 * ------------------------------------------------------------
 */

$container->register('session', function ($container) {
    $sessionName = get_env('SESSION_NAME', 'lightpack');

    return new Session($sessionName);
});

/**
 * ------------------------------------------------------------
 * Register Templating Service Provider.
 * ------------------------------------------------------------
 */

$container->register('template', function ($container) {
    return new Template();
});

/**
 * ------------------------------------------------------------
 * Register Modules Service Provider.
 * ------------------------------------------------------------
 */

$container->register('module', function ($container) {
    return new Module(
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
    return new Route(
        $container->get('request')
    );
});

/**
 * ------------------------------------------------------------
 * Register URL Router Service.
 * ------------------------------------------------------------
 */

$container->register('router', function ($container) {
    return new Router(
        $container->get('request'),
        $container->get('route')
    );
});

/**
 * ------------------------------------------------------------
 * Register Cache Service Provider.
 * ------------------------------------------------------------
 */

$container->register('cache', function ($container) {
    $cacheDir = $container->get('config')->get('cache.storage');
    $fileStorage = new FileDriver($cacheDir);

    return new Cache($fileStorage);
});

/**
 * ------------------------------------------------------------
 * Register log service provider.
 * ------------------------------------------------------------
 */

$container->register('logger', function ($container) {
    $logDriver = new NullLogger;

    if ('file' === get_env('LOG_DRIVER')) {
        $logDriver = new FileLogger(
            $container->get('config')->get('log.filename')
        );
    }

    return new Logger($logDriver);
});

/**
 * ------------------------------------------------------------
 * Register Database Service Manager.
 * ------------------------------------------------------------
 */

$container->register('db', function ($container) {
    $config = $container->get('config');

    switch ($config->get('db.driver')) {
        case 'sqlite':
            return new Sqlite([
                'database' => $config->get('db.sqlite.database')
            ]);

            break;
        case 'mysql':
            return new Mysql([
                'host'      => $config->get('db.mysql.host'),
                'port'      => $config->get('db.mysql.port'),
                'username'  => $config->get('db.mysql.username'),
                'password'  => $config->get('db.mysql.password'),
                'database'  => $config->get('db.mysql.database'),
                'options'   => $config->get('db.mysql.options'),
            ]);

            break;
        default:
            throw new Exception(
                'Unsupported database driver type: ',
                $config->get('db.driver')
            );
    }
});
