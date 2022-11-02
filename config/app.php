<?php

return [
    'app.env' => get_env('APP_ENV', 'development'),
    'app.url' => get_env('APP_URL', 'http://localhost'),
    'app.timezone' => 'UTC',
    'app.locale' => 'en',
    'app.key' => get_env('APP_KEY'),
];
