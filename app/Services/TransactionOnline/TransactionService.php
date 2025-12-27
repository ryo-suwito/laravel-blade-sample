<?php

namespace App\Services\TransactionOnline;

use App\Services\APIService;

class TransactionService extends APIService
{
    public function paginate(array $params = [])
    {
        return $this->client()->get('transaction-online/transactions', $params);
    }
}
