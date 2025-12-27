<?php

namespace App\Http\Controllers\JSON\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roles;

    public function __construct()
    {
        $this->roles = api('store_management', 'role');
    }

    public function index(Request $request)
    {
        $response = $this->roles->paginated(
            $request->only('page', 'search')
        );

        apiResponseHandler($response, false);

        return response()->json($response->json('result'));
    }
}
