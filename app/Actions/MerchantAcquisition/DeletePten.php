<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;

class DeletePten
{
    protected $deletePten;

    public function __construct()
    {
        $this->deletePten = API::instance('merchant_acquisition', 'delete_pten');
    }

    public function update(array $ids, string $reason)
    {
        $response = $this->deletePten->delete(['ids' => $ids, 'reason' => $reason]);
        
        apiResponseHandler($response, false);

        return $response->json('result');
    }
}