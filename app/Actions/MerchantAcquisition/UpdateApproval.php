<?php

namespace App\Actions\MerchantAcquisition;

use App\Helpers\H;
use App\Services\API;

class UpdateApproval
{
    protected $approvals;

    public function __construct()
    {
        $this->approvals = API::instance('merchant_acquisition', 'approvals');
    }

    public function update(string $id, array $payload)
    {
        $response = $this->approvals->update($id, $payload);

        apiResponseHandler($response, function($response) {
            if ($response->json('status_code') == '42201') {
                throw new \Exception($response->json('status_message'));
            }

            return false;
        });

        return $response->json('result');
    }
}