<?php

/**
 * Register app filters.
 */

return [
    'filters' => [
        'csrf' => \App\Filters\CsrfFilter::class,
        'cors' => \App\Filters\CorsFilter::class,
    ],
];
