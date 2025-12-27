<?php

namespace App\Services\OTP;

class VerifyOtpRequest
{
    protected string $method = 'POST';

    protected array $payload = [];

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

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
        return '/verify-otp';
    }

    /**
     * @return array
     */
    protected function defaultHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . AccessToken::get(),
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
        return array_merge([
            'user_type' => 'STORE_USER',
            'user_id' => '',
            'target' => '',
            'action' => 'LOGIN',
            'source' => 'CMS_DASHBOARD',
            'token' => '',
            'remember' => [
                'token' => null,
            ],
            'meta' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        ], $this->payload);
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
