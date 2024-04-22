<?php

/**
 * The Mr Messaging api credentials
 */

return [
    'username' => env('MR_MESSAGING_USERNAME'),
    'password' => env('MR_MESSAGING_PASSWORD'),
    'host' => env('MR_MESSAGING_HOST', 'https://api.mrmessaging.net/'),
    'delivery_enabled' => env('MR_MESSAGING_DELIVERY_ENABLED', true),
    'store_event_id' => [
        'cache' => [
            'enabled' => env('MR_MESSAGING_STORE_CACHE_ENABLED', false),
            'ttl' => env('MR_MESSAGING_STORE_CACHE_TTL', 86400) // 24 hours,
        ],
    ],
];
