<?php

namespace App\Services\PaymentGateway;

class PaymentGateway
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'credential' => new PartnerCredentialService(),
            'partner' => new PartnerService(),
            'provider' => new ProviderService(),
            'payment_channel' => new PaymentChannelService(),
            'payment_link' => new PaymentLinkService,
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
