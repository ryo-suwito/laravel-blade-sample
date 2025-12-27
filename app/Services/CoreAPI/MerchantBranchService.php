<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class MerchantBranchService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->get('core-api/merchant-branches', $params);
    }
}
