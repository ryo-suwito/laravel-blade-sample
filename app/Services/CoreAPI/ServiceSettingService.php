<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class ServiceSettingService extends APIService
{
    public function find($id)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get('core-api-v3/platform-setting/' . $id . '/find');
    }

    public function update($id, array $data)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get('core-api-v3/platform-setting/' . $id . '/update', $data);
    }
}
