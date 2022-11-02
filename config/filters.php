<?php

/**
 * ------------------------------------------------------------
 * Register app filters.
 * ------------------------------------------------------------
 */

return [
    'filters' => [
        'csrf' => Lightpack\Filters\CsrfFilter::class,
        'cors' => Lightpack\Filters\CorsFilter::class,
        'auth' => Lightpack\Filters\AuthFilter::class,
    ],
];
