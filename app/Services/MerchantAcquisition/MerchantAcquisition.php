<?php

namespace App\Services\MerchantAcquisition;

class MerchantAcquisition
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'options' => new OptionService(),
            'approvals' => new ApprovalService(),
            'delete_pten'=> new DeletePtenService(),
            'owners' => new OwnerService()
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
