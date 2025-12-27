<?php

namespace App\Services\ClientManagement;

use App\Services\APIService;

class ClientService extends APIService
{
    public function paginate(array $params)
    {
        return $this->client()->get('client-management/clients', $params);
    }

    public function find($id)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get('client-management/clients/' . $id . '/find');
    }

    public function update($id, array $data)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->put('client-management/clients/' . $id . '/update', $data);
    }

    public function createClient(array $params = [])
    {
        return $this->client()->post("client-management/clients/create", $params);
    }

    public function createPublicKey(array $params = [])
    {
        return $this->client()->post("client-management/rsa-keys", $params);
    }

    public function updatePublicKey($id, array $params = [])
    {
        return $this->client()->segment([
            'id' => 3,
        ])->patch("client-management/rsa-keys/{$id}", $params);
    }
}
