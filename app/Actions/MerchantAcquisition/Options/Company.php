<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;

class Company
{
    protected $data;

    public function __construct()
    {
        $this->data = API::instance('merchant_acquisition', 'options');
    }

    public function get(array $filter)
    {
        $response = $this->data->company($filter);

        apiResponseHandler($response, false);

        return $response->json('result');
    }
}
