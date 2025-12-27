<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;

class GetStatusCounter
{
    protected $approvals;

    public function __construct()
    {
        $this->approvals = API::instance('merchant_acquisition', 'approvals');
    }

    public function get(string $tableName)
    {
        $response = $this->approvals->statusCounter([
            'table_name' => $tableName,
        ]);
        
        apiResponseHandler($response, false);

        return $response->json('result');
    }
}