<?php

namespace App\Http\Controllers\JSON\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantBranchController extends Controller
{
    protected $merchantBranches;

    public function __construct()
    {
        $this->merchantBranches = api('core_api', 'merchant_branch');
    }

    public function index(Request $request)
    {
        $response = $this->merchantBranches->paginated(
            $request->only('page', 'search')
        );

        apiResponseHandler($response, false);

        return response()->json($response->json('result'));
    }
}
