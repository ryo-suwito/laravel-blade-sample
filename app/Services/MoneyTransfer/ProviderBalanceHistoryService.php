<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class ProviderBalanceHistoryService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCE_HISTORIES_GET_HISTORIES, $params);
    }    

    public function summaryDetail(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_BALANCES_GET_DETAIL_SUMMARY, $params)->throw();
    }
}