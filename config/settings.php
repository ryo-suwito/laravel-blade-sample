<?php

return [
    'env' => [
        'production' => [
            "url" => config('app.env') == 'production' ? config('app.url') : env('PRODUCTION_APP_URL', 'http://localhost:3025')
        ],
        'sandbox' => [
            "url" => config('app.env') == 'sandbox' ? config('app.url') : env('SANDBOX_APP_URL', 'http://localhost:3025')
        ],
    ]
];