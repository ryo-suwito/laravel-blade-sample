<?php

namespace App\Services\OTP;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OTP
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'OTP' => new OtpService,
        ]);
    }

    public function services($name = null)
    {
        if ($name == null) {
            return $this->services;
        }

        return $this->services->get($name);
    }

    public static function instance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return string
     */
    protected function baseUrl()
    {
        return config('otp.base_url');
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
     * @return \Illuminate\Http\Client\Response
     */
    public function send($service)
    {
        $method = strtolower($service->method());

        return Http::baseUrl($this->baseUrl())
            ->withUserAgent('YUKK/CMS-Dashboard:1.0')
            ->withHeaders(array_merge(
                $this->headers(),
                $service->headers(),
            ))
            ->retry(2, 0, function (RequestException $e) {
                if ($e->response->unauthorized()) {
                    AccessToken::forget();

                    return true;
                }

                return false;
            })
            ->{$method}($service->endpoint(), $service->body());
    }
}
