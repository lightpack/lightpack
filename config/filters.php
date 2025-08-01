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
        'guest' => Lightpack\Filters\GuestFilter::class,
        'limit' => Lightpack\Filters\LimitFilter::class,
        'signed' => Lightpack\Filters\SignedFilter::class,
        'verified' => Lightpack\Filters\VerifyEmailFilter::class,
        'mfa' => Lightpack\Filters\MfaFilter::class,
    ],
];
