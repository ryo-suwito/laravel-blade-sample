<?php

namespace App\Repositories\StoreManagement;

use App\Services\APIService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UserSettingRepository extends APIService {
    public function activateSandbox(array $payload)
    {
        return $this->client()->post('store/user-settings/activate-sandbox/pg', $payload);
    }
}
