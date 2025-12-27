<?php

namespace App\Services\StoreManagement;

class StoreManagement
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'auth' => new AuthService(),
            'role' => new RoleService(),
            'user' => new UserService(),
        ]);
    }

    public function services($name = null)
    {
        if ($name == null) {
            return $this->services;
        }

        return $this->services->get($name);
    }

    public static function instance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
