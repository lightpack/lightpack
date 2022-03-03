<?php

use Lightpack\Auth\Identifiers\DefaultIdentifier;

return [
    'auth' => [
        'default' => [
            'identifier' => DefaultIdentifier::class,
            'login.url' => 'dashboard/login',
            'logout.url' => 'dashboard/logout',
            'login.redirect' => 'dashboard/home',
            'logout.redirect' => 'dashboard/login',
            'fields.id' => 'id',
            'fields.username' => 'email',
            'fields.password' => 'password',
            'fields.api_token' => 'api_token',
            'fields.remember_token' => 'remember_token',
            'fields.last_login_at' => 'last_login_at',
            'flash_error' => 'Invalid account credentials.',
        ],
    ],
];
