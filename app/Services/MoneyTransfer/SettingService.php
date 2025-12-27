<?php

namespace App\Services\MoneyTransfer;

use App\Services\APIService;

class SettingService extends APIService
{
    public function all()
    {
        return $this->client()->get("money-transfer/settings")->throw();
    }

    public function get(string $name = '')
    {
        $name = strtolower($name);
        
        return $this->client()->segment([
            'name' => 3
        ])->get("money-transfer/settings/{$name}")->throw();
    }    

    public function update(array $params)
    {
        return $this->client()->put("money-transfer/settings", $params)->throw();
    }
}