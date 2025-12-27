<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;

class GetMaster
{
    protected $approvals;

    public function __construct()
    {
        $this->approvals = API::instance('merchant_acquisition', 'approvals');
    }

    public function get(string $id, array $payload)
    {
        $response = $this->approvals->showMaster($id, $payload);
        
        apiResponseHandler($response, false);

        return $response->json('result');
    }
}