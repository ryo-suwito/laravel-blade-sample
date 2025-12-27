<?php

namespace App\Services\MerchantAcquisition;

use App\Services\APIService;
use Illuminate\Support\Facades\Http;

class DeletePtenService extends APIService
{
    public function get(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/merchant_branch/pten-delete/list", $payload);
    }

    public function delete(array $payload = [])
    {
        return $this->client()->post("merchant-acquisition/merchant_branch/pten-delete/delete", $payload);
    }

    public function getActivity(array $payload = [])
    {
        return $this->client()->get("merchant-acquisition/merchant_branch/pten-delete/list-activity", $payload);
    }

    public function import($file)
    {
        return $this->client()
                ->attach('file', $file->get(), 'import_delete_pten.xlsx')
                ->post('merchant-acquisition/merchant_branch/pten-delete/import');
    }
}