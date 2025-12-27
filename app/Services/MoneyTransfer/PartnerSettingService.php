<?php

namespace App\Services\MoneyTransfer;

use App\Helpers\ApiHelper;
use App\Services\APIService;

class PartnerSettingService extends APIService
{
    public function getEntities(array $params = [])
    {
        return $this->client()->get("money-transfer/partner-setting/entities", $params);
    }

    public function getOptionEntities(array $params = [])
    {
        return $this->client()->get("money-transfer/partner-setting/options/entities", $params);
    }

    public function store(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_STORE, $params);
    }

    public function show(int $id, $params = [])
    {
        return $this->client()->segment([
            'id' => 4,
        ])->get(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_GET_PARTNERS.'/'.$id, $params);
    }

    public function update(int $id, array $params = [])
    {
        return $this->client()->segment([
            'id' => 4,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_GET_PARTNERS. '/' .$id. '/update', $params);
    }

    public function userStore(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_USER_STORE, $params);
    }

    public function bankStore(array $params = [])
    {
        return $this->client()->post(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_PAYMENT_CHANNELS_STORE, $params);
    }

    public function bankUpdate(int $id, array $params = [])
    {
        return $this->client()->segment([
            'partner_id' => 4,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_PAYMENT_CHANNELS. '/' .$id. '/banks/update', $params);
    }

    public function userUpdate(int $id, array $params = [])
    {
        return $this->client()->segment([
            'user_id' => 4,
        ])->put(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_PAYMENT_CHANNELS. '/' .$id. '/user/update', $params);
    }

    public function getConfig()
    {
        return $this->client()->get(ApiHelper::END_POINT_MONEY_TRANSFER_PARTNER_SETTINGS_GET_CONFIG);
    }

    public function getCredential(array $params = [])
    {
        return $this->client()->get("money-transfer/entity-setting/credential", $params);
    }
}