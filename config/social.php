<?php

return [
    'social' => [
        'user' => [
            'provider' => App\Models\User::class
        ],
        'providers' => [
            'google' => [
                'provider' => Lightpack\SocialAuth\Providers\GoogleProvider::class,
                'client_id' => get_env('GOOGLE_CLIENT_ID'),
                'client_secret' => get_env('GOOGLE_CLIENT_SECRET'),
                'scopes' => ['email', 'profile'],
                'redirect_uri' => get_env('APP_URL') . '/auth/google/callback',
            ],
            'github' => [
                'provider' => Lightpack\SocialAuth\Providers\GitHubProvider::class,
                'client_id' => get_env('GITHUB_CLIENT_ID'),
                'client_secret' => get_env('GITHUB_CLIENT_SECRET'),
                'scopes' => ['user:email'],
                'redirect_uri' => get_env('APP_URL') . '/auth/github/callback',
            ],
            'linkedin' => [
                'provider' => Lightpack\SocialAuth\Providers\LinkedInProvider::class,
                'client_id' => get_env('LINKEDIN_CLIENT_ID'),
                'client_secret' => get_env('LINKEDIN_CLIENT_SECRET'),
                'scopes' => ['openid', 'profile', 'email'],
                'redirect_uri' => get_env('APP_URL') . '/auth/linkedin/callback',
            ],
        ],
    ],
];
