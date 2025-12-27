<?php

namespace App\Actions\MerchantBranch;

class GetEdcType
{

    public function handle($edc): string
    {
        $isPaymentGateway = collect($edc->partner_logins)
        ->where('is_payment_gateway', 1)
        ->first();

        if ($isPaymentGateway) {
            return 'QRIS_PG';
        }
        
        return $edc->type;
    }
}