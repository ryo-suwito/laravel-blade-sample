<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class SettingService extends APIService
{
    public function paginated(int $perPage, int $page, $keyword)
    {
        return $this->client()->get('core-api-v3/settings', [
            'per_page' => $perPage,
            'page' => $page,
            "keyword" => $keyword
        ]);
    }

    public function show(int $id)
    {
        return $this->client()->segment([
            'id' => 3
        ])->get("core-api-v3/settings/{$id}");
    }

    public function update(int $id, string $value, int $active)
    {
        return $this->client()->segment([
            'id' => 3
        ])->put("core-api-v3/settings/{$id}", [
            "value" => $value,
            "active" => $active
        ]);
    }
}
