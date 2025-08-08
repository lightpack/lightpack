<?php

/**
 * --------------------------------------------------
 * Register API routes here.
 * --------------------------------------------------
 * 
 * It has been pre-configured with CORS filter so
 * any cross-origin API request with HTTP OPTIONS
 * method will be served accordingly.
 */

route()->group(['prefix' => '/api', 'filter' => ['cors']], function () {
    route()->options('/:any', Lightpack\Controllers\CorsController::class);
});
