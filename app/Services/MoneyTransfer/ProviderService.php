<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class ProviderService extends APIService
{
    public function list()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDERS)->throw();
    }

    public function getConfig()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDER_CONFIG);
    }

    public function show($id)
    {
        return $this->client()->segment([
            'id' => 3
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDERS .'/'. $id);
    }

    public function update(int $id, array $params = [])
    {
        return $this->client()->segment([
            'id' => 3,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDERS. '/' .$id. '/update', $params);
    }

    public function bankStore(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_PAYMENT_CHANNELS_STORE, $params);
    }

    public function bankUpdate(int $id, array $params = [])
    {
        return $this->client()->segment([
            'provider_id' => 4,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PROVIDER_GET_PROVIDERS. '/payment-channels/' .$id. '/banks/update', $params);
    }

    public function getInquiryProviders()
    {
        return $this->client()->get("money-transfer/inquiry-providers");
    }
}