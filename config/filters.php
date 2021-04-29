<?php

/**
 * Register app filters.
 */

return [
    'csrf' => \App\Filters\CsrfFilter::class,
    'cors' => \App\Filters\CorsFilter::class,
];