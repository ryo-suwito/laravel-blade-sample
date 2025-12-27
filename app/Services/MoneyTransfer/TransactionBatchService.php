<?php

namespace App\Services\MoneyTransfer;

use App\Services\APIService;

class TransactionBatchService extends APIService
{
    public function paginate(array $params = [])
    {
        return $this->client()->get('money-transfer/transactions/batches', $params)->throw();
    }

    public function get(string $code)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/batches/' . $code)->throw();
    }

    public function items(string $code, array $payload)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/batches/' . $code .'/items', $payload)->throw();
    }
}
