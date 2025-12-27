<?php

return [
        'client_id' => env('OTP_CLIENT_ID'),
        'client_secret' => env('OTP_CLIENT_SECRET'),
        'client_scope' => 'otp',
        'base_url' => env('OTP_HOST') . '/api',
        'provider' => env('OTP_PROVIDER', 'log'),
        'channel' => env('OTP_CHANNEL', 'mail'),
        'expires_in' => env('OTP_EXPIRES_IN', 120),

        'verification' => [
                'enabled' => env('OTP_VERIFICATION_ENABLED', 0),
                'users' => explode(',', env('OTP_VERIFICATION_USERS', '')),
        ],
 ];
