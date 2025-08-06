<?php

return [
    'captcha' => [
        // native, recaptcha, turnstile, null
        'driver' => get_env('CAPTCHA_DRIVER', 'native'),
    
        'native' => [
            'font' => DIR_PUBLIC . '/fonts/arial.ttf',
            'width' => 150,
            'height' => 50,
        ],
        'recaptcha' => [
            'site_key' => get_env('RECAPTCHA_SITE_KEY'),
            'secret_key' => get_env('RECAPTCHA_SECRET_KEY'),
        ],
        'turnstile' => [
            'site_key' => get_env('TURNSTILE_SITE_KEY'),
            'secret_key' => get_env('TURNSTILE_SECRET_KEY'),
        ]
    ],
];
