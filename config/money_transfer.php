<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'shared_key' => env('MONEY_TRANSFER_SHARED_KEY'),

    'cipher' => env('MONEY_TRANSFER_CIPHER', 'AES-256-CBC'),
    
    'url' => env('MONEY_TRANSFER_URL', 'http://localhost:4006'),

    'login' => [
        'path' => 'v2/dashboard/auth/:token',
        'expires_in' => env('MONEY_TRANSFER_TOKEN_EXPIRES_IN', 30)
    ]
];
