<?php

namespace Tests;

use App\Services\StoreManagement\AuthService;

trait HasAuth
{
    protected $user;

    public function actingAsStoreManagementUser($username = null, $password = null)
    {
        $username = 'alex@yukk.me';
        $password = 'Password123';

        $res = (new AuthService())->login(compact('username', 'password'));

        if (! $res->ok()) {
            return;
        }

        $this->user = (object) $res->json('result');

        session()->put('jwt_token', $this->user->jwt_token);
    }
}
