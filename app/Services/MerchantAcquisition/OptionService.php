<?php

namespace App\Services\MerchantAcquisition;

use App\Services\APIService;

class OptionService extends APIService
{
    public function company(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/options/companies", $payload);
    }

    public function merchant(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/options/merchants", $payload);
    }

    public function branch(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/options/merchant_branches", $payload);
    }

    public function customer(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/options/customers", $payload);
    }
}
