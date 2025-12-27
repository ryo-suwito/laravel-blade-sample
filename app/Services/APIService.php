<?php

namespace App\Services;

class APIService
{
    protected $client;

    public function __construct()
    {
        $this->client = apiGateway()->client();
    }

    protected function defaultHeadaers()
    {
        return [
            'Authorization' => 'Bearer ' . session()->get('jwt_token'),
            'Accept' => 'application/json',
        ];
    }

    public function client()
    {
        return tap($this->client, function ($client) {
            $client->defaultHeaders($this->defaultHeadaers());
        });
    }
}
