<?php

namespace App\Actions\MerchantBranch;

class GetEdcTypeTags
{

    public function handle(array $edcs): string
    {
        return collect($edcs)
            ->groupBy(function ($item) {

                if($item->partner_logins != []){
                    $isPaymentGateway = collect($item->partner_logins)
                    ->where('is_payment_gateway', 1)
                    ->first();

                    if ($isPaymentGateway) {
                        return 'QRIS_PG';
                    }
                }
                
                return $item->type;
            })
            ->keys()
            ->map(function ($item) {

                if($item == 'QRIS_DYNAMIC'){
                    return "<span class='badge badge-warning'>{$item}</span>";
                }else{
                    return "<span class='badge badge-info'>{$item}</span>";
                }
                
            })
            ->implode(' ');
    }
}