<?php

return [
    'base_url' => env('BASE_URL_WEBSITE', 'http://localhost/yukk-website/public/'),

    'api_base_url' => env('BASE_URL_WEBSITE', 'http://localhost/yukk-website/public/api/')."api/",

    'end_point' => [
        'encrypt_email' => 'encrypt/',
        'get_on_boarding_list' => 'on-boarding/get-list'
    ]
];
