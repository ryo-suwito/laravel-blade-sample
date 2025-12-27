<?php

namespace App\Services\PaymentGateway;

use App\Services\APIService;

class PaymentChannelService extends APIService
{
    public function all(string $filter, string $id)
    {
        return $this->client()->get("pg-core-service/payment-channels", [
            $filter => $id
        ]);
    }
}