<?php

namespace App\Services\StoreManagement;

use App\Services\CoreAPI\BeneficiaryService;
use App\Services\CoreAPI\MerchantBranchService;
use App\Services\CoreAPI\PartnerService;

class TargetIdService {
    protected $partnerService;

    protected $beneficiaryService;

    protected $merchantBranchService;

    public function __construct() {
        $this->partnerService = new PartnerService;
        $this->beneficiaryService = new BeneficiaryService;
        $this->merchantBranchService = new MerchantBranchService;
    }
    
    public function __call($name, $args)
    {
        $method = str_replace("_", " ", $name);
        $method = ucwords(strtolower($method));
        $method = str_replace(" ", "", $method);

        return $this->{$method}(...$args);
    }

    public function find(string $targetType, $targetIds) {
        return $this->{$targetType}($targetIds);
    }

    private function partner($ids) {
        return $this->partnerService->paginated([
            'id' => $ids
        ]);
    }

    private function beneficiary($ids) {
        return $this->beneficiaryService->paginated([
            'id' => $ids
        ]);
    } 

    private function merchantBranch($ids) {
        return $this->merchantBranchService->paginated([
            'id' => $ids
        ]);
    }
}