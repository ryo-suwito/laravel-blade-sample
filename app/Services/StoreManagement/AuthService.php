<?php

namespace App\Services\StoreManagement;

use App\Services\APIService;

class AuthService extends APIService
{
    public function login(array $body)
    {
        return $this->client->post('store/auth/login', $body);
    }

    public function logout(array $body)
    {
        return $this->client->post('store/auth/logout', $body);
    }
}
