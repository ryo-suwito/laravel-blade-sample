<?php

namespace App\Services\PaymentGateway;

use App\Services\APIService;

class PartnerService extends APIService
{
    public function get(string $partnerId)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get("pg-core-service/partner/{$partnerId}");
    }
}