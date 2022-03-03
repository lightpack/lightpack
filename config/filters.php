<?php

/**
 * ------------------------------------------------------------
 * Register app filters.
 * ------------------------------------------------------------
 */

return [
    'filters' => [
        'csrf' => \App\Filters\CsrfFilter::class,
        'cors' => \App\Filters\CorsFilter::class,
        'auth:web' => \App\Filters\Auth\WebFilter::class,
        'auth:api' => \App\Filters\Auth\ApiFilter::class,
    ],
];
