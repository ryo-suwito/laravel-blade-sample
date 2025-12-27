<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;
use App\Services\Paginator;

class GetDeletePten
{
    protected $deletePten;

    public function __construct()
    {
        $this->deletePten = API::instance('merchant_acquisition', 'delete_pten');
    }

    public function get(array $filter)
    {
        $response = $this->deletePten->get($filter);
        
        apiResponseHandler($response, false);

        return Paginator::fromResponse($response)->appends(collect($filter)->only('status', 'dates_by', 'start_date', 'end_date')->toArray());
    }
}