<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class BankService extends APIService
{
    public function assigned(string $relationName, int $relationId)
    {
        return $this->client()->segment([
            'relation_name' => 4,
            'relation_id' => 5,
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_BANKS. '/assigned/'. $relationName . '/' .$relationId);
    }

    public function nonAssigned(string $relationName, int $relationId)
    {
        return $this->client()->segment([
            'relation_name' => 4,
            'relation_id' => 5,
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_BANKS. '/non-assigned/'. $relationName . '/' .$relationId);
    }
}