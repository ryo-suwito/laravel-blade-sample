<?php

namespace App\Services\MoneyTransfer;

class MoneyTransfer
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'setting' => new SettingService(),
            'transaction_batch' => new TransactionBatchService(),
            'provider' => new ProviderService(),
            'transaction_item' => new TransactionItemService(),
            'transaction_group' => new TransactionGroupService(),
            'provider_balance_history' => new ProviderBalanceHistoryService(),
            'transfer_proxy' => new TransferProxyService(),
            'transaction' => new TransactionService(),
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
