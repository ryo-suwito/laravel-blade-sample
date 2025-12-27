<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\CoreAPI\BeneficiaryService;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    protected $service;

    public function __construct(
        BeneficiaryService $service
    ) {
        $this->service = $service;
    }
    
    public function index(Request $request) {
        $partners = $this->service->paginated($request->toArray());

        return response([
            'result' => $partners->json('result.data'),
            'more' => $partners->json('result.next_page_url') != null ? true : false,
            'page' => $partners->json('result.current_page'),
        ]);
    }
}
