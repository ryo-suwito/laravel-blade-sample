<?php

namespace App\Services\CMSAPI;

class CMSAPI
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'access_control' => new AccessControlService(),
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
