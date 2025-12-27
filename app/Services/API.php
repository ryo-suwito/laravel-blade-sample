<?php

namespace App\Services;

class API
{
    protected $namespace;

    protected static $instance;

    public function __construct()
    {
        $this->register();
    }

    public function register()
    {
        $this->namespace = collect([
            'cms_api' => CMSAPI\CMSAPI::instance(),
            'core_api' => CoreAPI\CoreAPI::instance(),
            'store_management' => StoreManagement\StoreManagement::instance(),
            'payment_gateway' => PaymentGateway\PaymentGateway::instance(),
            'transaction_online' => TransactionOnline\TransactionOnline::instance(),
            'merchant_acquisition' => MerchantAcquisition\MerchantAcquisition::instance(),
            'client_management' => ClientManagement\ClientManagement::instance(),
            'OTP' => OTP\OTP::instance(),
            'otp' => OTP\OTP::instance(),
            'getAccessToken' => GetAccessToken\getAccessToken::instance(),
            'money_transfer' => MoneyTransfer\MoneyTransfer::instance(),
        ]);
    }

    public function namespace($name)
    {
        return $this->namespace->get($name);
    }

    public static function instance($namespace = null, $service = null)
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        if ($namespace == null) {
            return static::$instance;
        }

        if ($service == null) {
            return static::$instance->namespace($namespace);
        }

        return static::$instance->namespace($namespace)->services($service);
    }
}
