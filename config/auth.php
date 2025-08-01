<?php

return [
    'auth' => [
        'drivers' => [
            'default' => [
                'model' => App\Models\User::class,
                'identifier' => Lightpack\Auth\Identifiers\DefaultIdentifier::class,
                'login.url' => 'login',
                'logout.url' => 'logout',
                'login.redirect' => 'dashboard',
                'logout.redirect' => 'login',
                'fields.id' => 'id',
                'fields.username' => 'email',
                'fields.password' => 'password',
                'fields.remember_token' => 'remember_token',
                'fields.last_login_at' => 'last_login_at',
                'flash_error' => 'Invalid account credentials.',
            ],
        ],
    ],
];
