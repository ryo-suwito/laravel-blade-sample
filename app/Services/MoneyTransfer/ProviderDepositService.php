<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class ProviderDepositService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_DEPOSITS, $params);
    }

    public function statusCounter()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_STATUS_COUNTER);
    }

    public function find($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_DEPOSITS.'/'.$id);
    }

    public function retry($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_DEPOSITS.'/'.$id."/retry");
    }

    public function markSuccess($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_DEPOSIT_GET_DEPOSITS.'/'.$id."/success");
    }
}
