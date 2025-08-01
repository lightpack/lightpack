<?php

return [
    'ai' => [
        'driver' => get_env('AI_PROVIDER'), // openai, anthropic, mistral, groq
        'cache_ttl' => 3600, // Cache responses for 1 hour by default
        'http_timeout' => 15, // 15 seconds HTTP timeout by default
        'temperature' => 0.7,
        'max_tokens' => 256,

        'providers' => [
            'openai' => [
                'key' => get_env('OPENAI_KEY'),
                'model' => 'gpt-3.5-turbo',
                'endpoint' => 'https://api.openai.com/v1/chat/completions',
            ],
            'anthropic' => [
                'key' => get_env('ANTHROPIC_KEY'),
                'model' => 'claude-3-7-sonnet-latest',
                'endpoint' => 'https://api.anthropic.com/v1/messages',
                'version' => get_env('ANTHROPIC_VERSION', '2023-06-01'),
            ],
            'mistral' => [
                'key' => getenv('MISTRAL_KEY'),
                'model' => 'mistral-small-latest', // Or 'mistral-small', 'mistral-large'
                'endpoint' => 'https://api.mistral.ai/v1/chat/completions',
            ],
            'groq' => [
                'key' => getenv('GROQ_KEY'),
                'model' => 'llama3-70b-8192', // Or 'llama3-8b-8192', 'mixtral-8x7b-32768', etc.
                'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
            ],
        ],
    ],
];
