<?php

namespace App\Services\MoneyTransfer;

use App\Services\APIService;

class TransactionGroupService extends APIService
{
    public function paginate(array $params = [])
    {
        return $this->client()->get('money-transfer/transactions/groups', $params)->throw();
    }

    public function get(string $code)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/groups/' . $code)->throw();
    }

    public function items(string $code, array $payload)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/groups/' . $code .'/items', $payload)->throw();
    }
}
