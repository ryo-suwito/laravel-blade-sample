<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class SuspectTransactionLogService extends APIService
{
    public function paginated(int $id, int $perPage, int $page, $type, $keyword, $dateRange)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get("core-api-v3/suspects/{$id}/transaction-logs", [
            'per_page' => $perPage,
            'page' => $page,
            'type' => $type,
            'keyword' => $keyword,
            'dateRange' => $dateRange
        ]);
    }
}
