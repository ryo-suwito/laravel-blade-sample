<?php

namespace App\Services\StoreManagement;

use App\Services\APIService;

class RoleService extends APIService
{
    public function paginated(array $params = [])
    {
        return $this->client()->get('store/roles', $params);
    }

    public function find($id)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get('store/roles/' . $id . '/find');
    }

    public function create(array $data)
    {
        return $this->client()->post('store/roles/create', $data);
    }

    public function update($id, array $data)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->put('store/roles/' . $id . '/update', $data);
    }

    public function getTargetTypes()
    {
        return $this->client()->get('store/roles/target-types');
    }
}
