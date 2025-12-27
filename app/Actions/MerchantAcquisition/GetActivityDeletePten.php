<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;

class GetActivityDeletePten
{
    protected $deletePten;

    public function __construct()
    {
        $this->deletePten = API::instance('merchant_acquisition', 'delete_pten');
    }

    public function get()
    {
        $response = $this->deletePten->getActivity();
        
        apiResponseHandler($response, false);

        return $response->json('result');
    }
}