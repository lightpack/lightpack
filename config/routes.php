<?php

use App\Controllers\HomeController;
use App\Controllers\Api\CorsController;

/**
 * Register app routes here.
 */

$route->get('/', HomeController::class);

/**
 * Register API routes here.
 */

$route->group(['filters' => ['cors']], function ($route) {
    $route->options('/api/:any', CorsController::class);
});