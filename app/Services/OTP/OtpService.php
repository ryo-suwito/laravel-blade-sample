<?php

namespace App\Services\OTP;

use App\Services\APIService;
use Illuminate\Support\Facades\Http;

class OtpService extends APIService
{
    public function sendOtp($data, $token)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post(config('otp.base_url') . '/send-otp', $data);
        return [
            'status' => $response->status(),
            'body' => json_decode($response->body(), true)
        ];
    }

    public function verifyOtp($data, $token)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post(config('otp.base_url') . '/verify-otp', $data);
        return [
            'status' => $response->status(),
            'body' => json_decode($response->body(), true)
        ];
    }

    public function verifySession($data, $token){
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post(config('otp.base_url') . '/verify-session', $data);
        return [
            'status' => $response->status(),
            'body' => json_decode($response->body(), true)
        ];
    }
}
