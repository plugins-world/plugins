<?php

return [
    'name' => 'LaravelJwtAuth',

    'route' => [
        'prefix' => 'auth',
        'api_middleware' => [],
    ],

    'jwt' => [
        'ttl' => env('JWT_TTL', 60 * 24 * 7), // unit: minutes
        'refresh_ttl' => env('JWT_REFRESH_TTL', 60 * 24 * 7 * 2), // unit: minutes
    ],

    'auth' => [
        'guards' => [
            'api' => [
                'driver' => 'jwt',
                'provider' => 'users',
            ],
            'api-admin' => [
                'driver' => 'jwt',
                'provider' => 'administrator',
            ],
        ],

        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => \Plugins\LaravelJwtAuth\Models\User::class,
            ],
            'administrator' => [
                'driver' => 'eloquent',
                'model' => \Plugins\LaravelJwtAuth\Models\Administrator::class,
            ],
        ]
    ]
];
