<?php

return [
    'base_url' => env('BASE_URL_INBOUND', 'http://localhost/yukk-inbound/public'),
    'api_base_url' => env('BASE_URL_API_INBOUND', 'http://localhost/yukk-inbound/public/api'),

    'end_point' => [
        'check_request_live' => 'live_request/',
        'proxy_image' => 'storage/p/'
    ]
];
