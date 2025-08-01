<?php

return [
    'mfa' => [
        // The default MFA factor if user hasn't chosen one
        'default' => get_env('MFA_DRIVER'),

        // Enforce MFA for all users (set to true to require MFA everywhere)
        'enforce' => true,

        // List of enabled factors
        'factors' => [
            'email',
            'sms',
            'totp',
            'backup_code',
            'null',
        ],

        'email' => [
            // Number of digits/characters in the OTP
            'code_length' => 6,

            // OTP type: 'numeric', 'alpha', 'alnum', or 'custom'
            'code_type' => 'numeric',

            // Custom charset for 'custom' type (ignored otherwise)
            'charset' => null,

            // Set to a fixed code for demo/testing (never in production!)
            'bypass_code' => null,

            // Time-to-live for the OTP (in seconds)
            'ttl' => 300,

            // Queue name for job dispatching (if using background jobs)
            'queue' => 'default',

            // Max attempts for sending the code (job retries)
            'max_attempts' => 1,

            // How long to wait before retrying a failed job
            'retry_after' => '60 seconds',

            // Mailer class
            'mailer' => Lightpack\Mfa\Mail\SendEmailMfaVerificationMail::class,

            // How long (in seconds) before the user can request another code.
            'resend_interval' => 5,

            // How many times a user can request a resend within the resend_interval window.
            'resend_max' => 1,
        ],

        'sms' => [
            'ttl' => 300,
            'resend_max' => 1,
            'resend_interval' => 10,
            'message' => 'Your verification code is: {code}',
            'code_length' => 6,
            'code_type' => 'numeric',
        ],

        'totp' => [
            'digits' => 6,
            'period' => 30, // seconds
            'secret_length' => 32,
        ],
    ],
];
