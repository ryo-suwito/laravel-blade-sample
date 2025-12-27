<?php

namespace App\Services\PaymentGateway;

use App\Services\APIService;

class ProviderService extends APIService
{
    public function all()
    {
        return $this->client()->get("pg-core-service/providers");
    }
}