<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class PartnerService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->get('core-api/partners', $params);
    }
}
