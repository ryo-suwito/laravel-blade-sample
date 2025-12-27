<?php

namespace App\Http\Controllers\Store;

use App\Helpers\ApiHelper;
use App\Services\CoreAPI\PartnerService;
use Illuminate\Http\Request;

class PartnerController {
    protected $partnerService;

    public function __construct(
        PartnerService $partnerService
    ) {
        $this->partnerService = $partnerService;
    }
    
    public function index(Request $request) {
        $partners = $this->partnerService->paginated($request->toArray());

        return response([
            'result' => $partners->json('result.data'),
            'more' => $partners->json('result.next_page_url') != null ? true : false,
            'page' => $partners->json('result.current_page'),
        ]);
    }
}