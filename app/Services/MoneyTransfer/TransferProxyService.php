<?php

namespace App\Services\MoneyTransfer;

use App\Services\APIService;

class TransferProxyService extends APIService
{
    public function get(array $payload)
    {
        return $this->client()->get('money-transfer/transfer-proxies', $payload)->throw();
    }

    public function statusCounter()
    {
        return $this->client()->get('money-transfer/transfer-proxies/status-counter')->throw();
    }

    public function find(string $code)
    {
        return $this->client()->segment([
            'code' => 3,
        ])->get('money-transfer/transfer-proxies/'. $code)->throw();
    }

    public function retry($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transfer-proxies/{$id}/update", [
            'action' => 'RETRY',
        ]);
    }

    public function update($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transfer-proxies/{$id}/update", [
            'action' => 'UPDATE',
        ]);
    }

    public function markAsSuccess($id)
    {
        return $this->client()->segment([
            "id" => 3,
        ])->patch("money-transfer/transfer-proxies/{$id}/update", [
            'action' => 'SUCCESS',
        ]);
    }

    public function bulkRetry(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transfer-proxies/bulk-update", [
            'retry' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkMarkAsSuccess(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transfer-proxies/bulk-update", [
            'success' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkMarkAsFailed(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transfer-proxies/bulk-update", [
            'failed' => 1,
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

    public function bulkUpdate(string $providerCode, array $codes)
    {
        return $this->client()->patch("money-transfer/transfer-proxies/bulk-update", [
            'provider' => $providerCode,
            'codes' => $codes
        ])->throw();
    }

}