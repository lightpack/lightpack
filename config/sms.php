<?php

return [
    'sms' => [
        'provider' => get_env('SMS_PROVIDER'), // 'twilio', null, log
        'twilio' => [
            'sid' => get_env('TWILIO_SID', 'your_account_sid'),
            'token' => get_env('TWILIO_TOKEN', 'your_auth_token'),
            'from' => get_env('TWILIO_FROM', 'your_twilio_phone_number'),
        ],
    ]
];