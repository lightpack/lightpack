<?php

/**
 * Register app routes here.
 */

$route->group(['namespace' => 'App\Controllers'], function($route) {
    $route->get('/', 'HomeController@index');
});