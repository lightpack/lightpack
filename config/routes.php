<?php

use App\Controllers\HomeController;
use App\Controllers\Api\CorsController;

/**
 * @var \Lightpack\Routing\Route
 */

$route = $container->get('route');

/**
 * --------------------------------------------------
 * Register web routes here.
 * --------------------------------------------------
 * 
 * Any route that is not prefixed by 'api' will
 * be considered as web route.
 */

$route->get('/', HomeController::class);

/**
 * --------------------------------------------------
 * Register API routes here.
 * --------------------------------------------------
 * 
 * It has been pre-configured with CORS filter so
 * any cross-origin API request will be served
 * accordingly.
 */

$route->group(['filters' => ['cors']], function ($route) {
    $route->options('/api/:any', CorsController::class);
});
