<?php

namespace App\Repositories\Inbound;

use App\Services\APIService;

class RequestRepository extends APIService
{
    public function getData()
    {
        return $this->client()->post('testing');
    }
}
