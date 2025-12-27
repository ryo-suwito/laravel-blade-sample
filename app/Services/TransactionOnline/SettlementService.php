<?php

namespace App\Services\TransactionOnline;

use App\Services\APIService;

class SettlementService extends APIService
{
    public function get(array $params = [])
    {
        return $this->client()->get('transaction-online/settlements', $params);
    }
}
