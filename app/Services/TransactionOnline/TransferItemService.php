<?php

namespace App\Services\TransactionOnline;

use App\Services\APIService;

class TransferItemService extends APIService
{
    public function paginate(array $params = [])
    {
        return $this->client()->get('transaction-online/transfer-items', $params);
    }

    public function retry(int $id)
    {
        return $this->client()
            ->segment(['id' => 3])
            ->post("transaction-online/transfer-items/{$id}/retry");
    }

    public function transactions(int $id)
    {
        return $this->client()
            ->segment(['id' => 3])
            ->post("transaction-online/transfer-items/{$id}/transactions");
    }
}
