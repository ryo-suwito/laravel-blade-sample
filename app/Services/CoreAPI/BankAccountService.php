<?php

namespace App\Services\CoreAPI;

use App\Services\APIService;

class BankAccountService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->segment([])->get('merchant-acquisition/bank-accounts/index', $params);
    }
}
