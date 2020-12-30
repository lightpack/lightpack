<?php

/**
 * Register app routes here.
 */

$route->group(['namespace' => 'App\Controllers'], function($route) {
    $route->get('/', 'HomeController@index');
    $route->get('/users', 'UserController@index');
    $route->get('/users/:num', 'UserController@findOne');
    $route->get('/users/:num/edit', 'UserController@showUserForm');

    $route->group(['filters' => ['csrf']], function($route) {
        $route->post('/users/:num/edit', 'UserController@postUserForm');
    });
});