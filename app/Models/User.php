<?php

namespace App\Models;

use Lightpack\Auth\Models\AuthUser;

class User extends AuthUser
{
    /**
     * Hide these fields when serializing the model.
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];
}
