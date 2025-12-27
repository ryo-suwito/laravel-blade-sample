<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class TransactionItemService extends APIService
{

    public function summaryDisbursement()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_TRANSACTION_GET_SUMMARY_DISBURSEMENT)->throw();
    }

    public function show($code)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/items/'.$code)->throw();
    }

    public function webhookSend($code)
    {
        return $this->client()->segment([
            'code' => 4
        ])->get('money-transfer/transactions/items/'.$code.'/webhooks/send')->throw();
    }
    
}