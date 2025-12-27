<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class SuspectService extends APIService
{
    public function paginated(int $perPage, int $page, $type, $keyword, $dateRange)
    {
        return $this->client()->get('core-api-v3/suspects', [
            'per_page' => $perPage,
            'page' => $page,
            'type' => $type,
            'keyword' => $keyword,
            'dateRange' => $dateRange
        ]);
    }

    public function show(int $id)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get("core-api-v3/suspects/{$id}");
    }

    public function update(int $id, string $status)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->put("core-api-v3/users/{$id}/suspect", [
            "status" => $status
        ]);
    }
}
