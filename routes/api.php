<?php

use App\Controllers\Api\CorsController;

/**
 * --------------------------------------------------
 * Register API routes here.
 * --------------------------------------------------
 * 
 * It has been pre-configured with CORS filter so
 * any cross-origin API request with HTTP OPTIONS
 * method will be served accordingly.
 */

route()->group(['filters' => ['cors']], function () {
    route()->options('/api/:any', CorsController::class);
});
