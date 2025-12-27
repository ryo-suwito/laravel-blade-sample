<?php

namespace App\Services\getAccessToken;

use App\Services\APIService;
use Illuminate\Support\Facades\Http;

class getAccessTokenAPI extends APIService
{
    public function getAccessToken()
    {
        $response = Http::post(config('otp.base_url') . '/oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => config('otp.client_id'),
            'client_secret' => config('otp.client_secret'),
            'scope' => config('otp.client_scope')
        ]);

        return json_decode($response, true);
    }
}