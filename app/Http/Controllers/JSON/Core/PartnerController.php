<?php

namespace App\Http\Controllers\JSON\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    protected $partners;

    public function __construct()
    {
        $this->partners = api('core_api', 'partner');
    }

    public function index(Request $request)
    {
        $response = $this->partners->paginated(
            $request->only('page', 'search')
        );

        apiResponseHandler($response, false);

        return response()->json($response->json('result'));
    }
}
