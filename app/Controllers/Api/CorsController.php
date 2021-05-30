<?php

namespace App\Controllers\Api;

class CorsController
{
    public function index()
    {
        /**
         * Nothing to do here.
         * 
         * When browser sends preflight request with OPTIONS method
         * to your APIs, App\Filters\CorsFilter is invoked by the 
         * framework itself.
         * 
         * The framework will match all route requests that start with
         * '/api' prefix and HTTP method 'OPTIONS' and will call the 
         * cors filter class to check the request headers to verify if
         * the cross domain API request is allowed.
         * 
         * It also sets the appropriate response headers with CORS 
         * configurations defined in 'config/cors.php' file.
         * 
         * You can modify the "before" and "after" method of the filter
         * class as per your project requirements. 
         * 
         * There is an excellent article about CORS requests that you
         * may consult for guidelines.
         * 
         * https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
         */
    }
}
