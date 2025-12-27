<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class BeneficiaryService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->get('core-api/beneficiaries', $params);
    }
}
