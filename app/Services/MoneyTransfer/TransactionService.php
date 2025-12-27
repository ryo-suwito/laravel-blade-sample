<?php

namespace App\Services\MoneyTransfer;

use App\Constants\MoneyTransfer\ProviderConstant;
use App\Helpers\ApiHelper;
use App\Services\APIService;

class TransactionService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_TRANSACTION_GET_TRANSACTIONS, $params)->throw();
    }

    public function statusCounter()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_TRANSACTION_GET_STATUS_COUNTER)->throw();
    }

    public function find($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_TRANSACTION_GET_TRANSACTIONS.'/'.$id);
    }

    public function update($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch(ApiHelper::END_POINT_MONEY_TRANSFER_TRANSACTION_GET_TRANSACTIONS.'/'.$id.'/update');
    }

    public function retry($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transactions/{$id}/update", [
            'retry' => 1,
        ]);
    }

    public function markAsSuccess($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transactions/{$id}/update", [
            'success' => 1,
        ]);
    }

    public function markAsFailed($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transactions/{$id}/update", [
            'failed' => 1,
        ]);
    }

    public function bulkRetry(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transactions/bulk-update", [
            'retry' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkMarkAsSuccess(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transactions/bulk-update", [
            'success' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkMarkAsFailed(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transactions/bulk-update", [
            'failed' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkUpdate(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transactions/bulk-update", [
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function getLogs(string $code, string $providerCode)
    {
        $filters = ['match' => ['context.request.body.partnerReferenceNo' => $code]];

        if($providerCode == ProviderConstant::YUKK) {
            $filters = ['match' => ['context.request.body.codes' => $code]];
        }

        return $this->client()->segment([
            'code' => 3
        ])->get('money-transfer/transactions/'.$code.'/log', [
            'index' => 'moneytransfer-' . strtolower($providerCode),
            'filters' => [
                $filters,
            ]
        ])->throw();
    }

    public function getErrorLogs(string $code)
    {
        return $this->client()->segment([
            'code' => 3
        ])->get('money-transfer/transactions/'.$code.'/log', [
            'index' => 'moneytransfer-laravel',
            'filters' => [
                ['match' => ['context.code' => $code]],
                ['match' => ['level_name' => 'EMERGENCY']],
            ]
        ])->throw();
    }
}
