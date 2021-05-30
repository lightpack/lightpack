<?php

/**
 * Register app routes here.
 */

$route->group(['namespace' => 'App\Controllers'], function($route) {
    $route->get('/', 'HomeController@index');
});

/**
 * Register API routes here.
 */

$route->group(['namespace' => 'App\Controllers\Api'], function ($route) {
    $route->group(['filters' => ['cors']], function ($route) {
        $route->options('/api/:any', 'CorsController@index');
    });
});