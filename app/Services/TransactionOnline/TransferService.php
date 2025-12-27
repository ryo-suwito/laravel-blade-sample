<?php

namespace App\Services\TransactionOnline;

use App\Services\APIService;

class TransferService extends APIService
{
    public function paginate(array $params = [])
    {
        return $this->client()->get('transaction-online/transfers', $params);
    }

    public function post(array $params = [])
    {
        return $this->client()->post('transaction-online/transfers', $params);
    }
}
