<?php

namespace App\Services\CoreAPI;

class CoreAPI
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'beneficiary' => new BeneficiaryService(),
            'merchant_branch' => new MerchantBranchService(),
            'partner' => new PartnerService(),
            'setting' => new SettingService(),
            'suspect' => new SuspectService(),
            'suspect_log' => new SuspectLogService(),
            'suspect_transaction_log' => new SuspectTransactionLogService(),
            'service_setting' => new ServiceSettingService(),
            'bank_account' => new BankAccountService(),
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
