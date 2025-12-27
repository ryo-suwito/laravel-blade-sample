<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class ProviderBalanceService extends APIService
{
    public function summary(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_GET_SUMMARY, $params);
    }

    public function cashout(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_CASHOUT, $params);
    }

    public function adjustment(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_ADJUSTMENT, $params);
    }

    // public function balanceUpdate(array $params = [])
    // {
    //     return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_BALANCE, $params);
    // }

    // public function uniqueUpdate(array $params = [])
    // {
    //     return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_UPDATE_UNIQUE_CODE, $params);
    // }
}