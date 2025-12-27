<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\StoreManagement\UserService;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    private $service;
 
    public function __construct(
        UserService $service
    )
    {
        $this->service = $service;

    }

    public function productionCheck(Request $request) {
        
        $response = $this->service->productionCheck($request->username);
        
        return response()->json(null, $response->status());
    }
}
