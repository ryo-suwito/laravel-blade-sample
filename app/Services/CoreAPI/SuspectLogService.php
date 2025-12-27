<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class SuspectLogService extends APIService
{
    public function paginated(int $id, int $perPage, int $page, $type = null, $keyword = null, $dateRange = null)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get("core-api-v3/suspects/{$id}/logs", [
            'per_page' => $perPage,
            'page' => $page,
            'type' => $type,
            'keyword' => $keyword,
            'dateRange' => $dateRange
        ]);
    }
}
