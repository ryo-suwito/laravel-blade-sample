<?php

namespace App\Services\TransactionOnline;

use App\Services\APIService;

class ManualTransferService extends APIService
{
    public function create(array $params = [])
    {
        return $this->client()->post('transaction-online/manual-transfers', $params);
    }
}
