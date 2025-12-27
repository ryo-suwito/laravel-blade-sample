<?php

namespace App\Services\Inbound;

use App\Services\APIService;

class RequestService extends APIService
{
    public function showData()
    {
        return $this->client()->post('inbound/production-table');
    }
}
