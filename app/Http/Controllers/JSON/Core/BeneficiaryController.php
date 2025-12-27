<?php

namespace App\Http\Controllers\JSON\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    protected $beneficiaries;

    public function __construct()
    {
        $this->beneficiaries = api('core_api', 'beneficiary');
    }

    public function index(Request $request)
    {
        $response = $this->beneficiaries->paginated(
            $request->only('page', 'search')
        );

        apiResponseHandler($response, false);

        return response()->json($response->json('result'));
    }
}
