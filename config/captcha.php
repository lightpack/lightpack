<?php

return [
    'captcha' => [
        // native, recaptcha, turnstile, null
        'driver' => 'native',
    
        'native' => [
            'font' => DIR_PUBLIC . '/fonts/arial.ttf',
            'width' => 150,
            'height' => 50,
        ],
        'recaptcha' => [
            'site_key' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'secret_key' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
        ],
        'turnstile' => [
            'site_key' => '1x00000000000000000000AA',
            'secret_key' => '1x0000000000000000000000000000000AA',
        ]
    ],
];
