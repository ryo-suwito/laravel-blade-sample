<?php

namespace App\Services\PaymentGateway;

use App\Services\APIService;

class PartnerCredentialService extends APIService
{
    public function all(string $partnerId)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get("pg-core-service/partner/{$partnerId}/credentials");
    }

    public function show(string $partnerId, string $token)
    {
        return $this->client()->segment([
            'id' => 3,
            'token' => 5,
        ])->get("pg-core-service/partner/{$partnerId}/credentials/{$token}");
    }

    public function store(string $partnerId, array $params)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->post("pg-core-service/partner/{$partnerId}/credentials", $params);
    }

    public function update(string $partnerId, string $token, array $params)
    {
        return $this->client()->segment([
            'id' => 3,
            'token' => 5,
        ])->put("pg-core-service/partner/{$partnerId}/credentials/{$token}", $params);
    }
}