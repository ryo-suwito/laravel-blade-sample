<?php

namespace App\Services\OTP;

class GetAcessTokenRequest
{
    protected string $method = 'POST';

    /**
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function endpoint()
    {
        return '/oauth/token';
    }

    /**
     * @return array
     */
    protected function defaultHeaders()
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * @return array
     */
    public function headers()
    {
        return array_merge($this->defaultHeaders(), [
            //
        ]);
    }

    /**
     * @return array
     */
    protected function defaultBody()
    {
        return [
            'client_id' => config('otp.client_id'),
            'client_secret' => config('otp.client_secret'),
            'grant_type' => 'client_credentials',
            'scope' => config('otp.client_scope'),
        ];
    }

    /**
     * @return array
     */
    public function body()
    {
        return array_merge($this->defaultBody(), [
            //
        ]);
    }
}
