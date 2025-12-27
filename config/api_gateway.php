<?php

return [
    'base_url' => env('API_GATEWAY_BASE_URL', 'http://localhost:8206'),

    'auth' => [
        'default' => env('API_GATEWAY_AUTH', 'store_management'),

        'providers' => [
            'store_management' => [
                'base_url' => env('STORE_MANAGEMENT_BASE_URL', 'http://localhost:8205'),
                'endpoints' => [
                    'check-access' => 'api/user/access-controls/can',
                    'check-any-access' => 'api/user/access-controls/has-any',
                    'check-all-access' => 'api/user/access-controls/has-all',
                ],
            ],
        ],
    ],

    'route' => [
        'model' => \App\Models\Route::class,

        'prefix' => 'p',
    ],

    'redirect' => [
        'login' => 'cms.login',
    ],
];
