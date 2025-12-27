<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;
use App\Services\Paginator;

class GetApprovals
{
    protected $approvals;

    public function __construct()
    {
        $this->approvals = API::instance('merchant_acquisition', 'approvals');
    }

    public function get(array $filter)
    {
        $response = $this->approvals->get($filter);
        
        apiResponseHandler($response, false);

        return Paginator::fromResponse($response)->appends(collect($filter)->only('status', 'dates_by', 'start_date', 'end_date')->toArray());
    }
}