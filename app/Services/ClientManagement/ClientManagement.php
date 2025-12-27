<?php

namespace App\Services\ClientManagement;

class ClientManagement
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'client' => new ClientService(),
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
