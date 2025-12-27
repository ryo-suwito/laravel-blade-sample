<?php

namespace App\Services\MerchantAcquisition;

use App\Services\APIService;

class ApprovalService extends APIService
{
    public function get(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/approvals", $payload);
    }

    public function statusCounter(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/approvals/status-counter", $payload);
    }

    public function find(string $id, array $payload)
    {
        return $this->client()->segment([
            'id' => 3
        ])->get("merchant-acquisition/approvals/{$id}", $payload);
    }

    public function update(string $id, array $payload)
    {
        return $this->client()->segment([
            'id' => 3
        ])->put("merchant-acquisition/approvals/{$id}", $payload);
    }

    public function showMaster(string $id, array $payload)
    {
        return $this->client()->segment([
            'id' => 3
        ])->get("merchant-acquisition/approvals/{$id}/show-master", $payload);
    }
}