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

route()->group(['filter' => ['cors']], function () {
    route()->options('/api/:path', CorsController::class)->pattern(['path' => ':any']);
});
