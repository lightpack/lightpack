<?php

 /**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Framework\Container\Container(); 

/**
 * ------------------------------------------------------------
 * Register Configuration Service Provider.
 * ------------------------------------------------------------
 */

$container->register('config', function($container) {
    return new Framework\Config\Config(['default', 'events', 'filters']);
});

/**
 * ------------------------------------------------------------
 * Register Global Event Handler.
 * ------------------------------------------------------------
 */

$container->register('event', function($container) {
    return new Framework\Event\Event();
});

/**
 * ------------------------------------------------------------
 * Register App Filter Service Provider.
 * ------------------------------------------------------------
 */

$container->register('filter', function($container) {
    return new Framework\Filters\Filter(
        $container->get('request'), 
        $container->get('response')
    );
});

/**
 * ------------------------------------------------------------
 * Register Incoming HTTP Request.
 * ------------------------------------------------------------
 */

$container->register('request', function($container) {
    return new Framework\Http\Request();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Response.
 * ------------------------------------------------------------
 */

$container->register('response', function($container) {
    return new Framework\Http\Response();
});

/**
 * ------------------------------------------------------------
 * Register HTTP Cookie.
 * ------------------------------------------------------------
 */

$container->register('cookie', function($container) {
    return new Framework\Http\Cookie(
        $container->get('config')->cookie_secret
    );
});

/**
 * ------------------------------------------------------------
 * Register App Session Handler.
 * ------------------------------------------------------------
 */

$container->register('session', function($container) {
    return new Framework\Http\Session(SESSION_NAME);
});

/**
 * ------------------------------------------------------------
 * Register Templating Service Provider.
 * ------------------------------------------------------------
 */

$container->register('template', function($container) {
    return new Framework\View\Template();
});

/**
 * ------------------------------------------------------------
 * Register Modules Service Provider.
 * ------------------------------------------------------------
 */

$container->register('module', function($container) {
    return new Framework\Module\Module(
        $container->get('request'),
        $container->get('config')
    );
});

/**
 * ------------------------------------------------------------
 * Register Application Route Manager.
 * ------------------------------------------------------------
 */

$container->register('route', function($container) {
    return new Framework\Routing\Route(
        $container->get('request')
    );
});

/**
 * ------------------------------------------------------------
 * Register URL Router Service.
 * ------------------------------------------------------------
 */

$container->register('router', function($container) {
    return new Framework\Routing\Router(
        $container->get('request'),
        $container->get('route')
    );
});

/**
 * ------------------------------------------------------------
 * Register MySQL Database Service Manager.
 * ------------------------------------------------------------
 */

$container->register('db', function($container) {
    return new Framework\Database\Adapters\MySql(
        $container->get('config')->default['connection']['mysql']
    );
});