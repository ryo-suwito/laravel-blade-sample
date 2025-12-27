<?php

namespace App\Services\OTP;

use Illuminate\Support\Facades\Cache;

class AccessToken
{
    protected static $cacheKey = 'otp.access_token';

    /**
     * @return null|string
     */
    public static function get()
    {
        if (Cache::has(static::$cacheKey)) {
            return Cache::get(static::$cacheKey);
        }

        $response = api('otp')->send(new GetAcessTokenRequest)->throw();

        Cache::put(static::$cacheKey, $response->json('access_token'), $response->json('expires_in'));

        return $response->json('access_token');
    }

    /**
     * @return bool
     */
    public static function forget()
    {
        return Cache::forget(static::$cacheKey);
    }
}
