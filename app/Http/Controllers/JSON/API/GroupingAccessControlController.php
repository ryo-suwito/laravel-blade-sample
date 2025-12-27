<?php

namespace App\Http\Controllers\JSON\API;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;

class GroupingAccessControlController extends Controller
{
    protected $accessControls;

    public function __construct()
    {
        $this->accessControls = API::instance('cms_api', 'access_control');
    }

    public function index(Request $request)
    {
        $response = $this->accessControls->grouping($request->get('target_type'));

        apiResponseHandler($response, false)->failedView();

        return response()->json($response->json());
    }
}
