<?php

namespace App\Services\CMSAPI;

use App\Services\APIService;

class AccessControlService extends APIService
{
    public function all(?string $targetType)
    {
        return $this->client()->get('api-gateway/access-controls', [
            'target_type' => $targetType,
        ]);
    }

    public function grouping(?string $targetType)
    {
        return $this->client()->get('api-gateway/access-controls/grouping', [
            'target_type' => $targetType,
        ]);
    }
}
