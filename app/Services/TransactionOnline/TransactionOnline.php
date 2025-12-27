<?php

namespace App\Services\TransactionOnline;

class TransactionOnline
{
    protected $services = null;

    protected static $instance;

    public function __construct()
    {
        $this->services = collect([
            'settlement' => new SettlementService,
            'transaction' => new TransactionService,
            'transfer' => new TransferService,
            'transfer_item' => new TransferItemService,
            'manual_transfer' => new ManualTransferService,
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
