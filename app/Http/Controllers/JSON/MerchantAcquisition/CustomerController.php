<?php

namespace App\Http\Controllers\JSON\MerchantAcquisition;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = api('merchant_acquisition', 'options');
    }

    public function index(Request $request)
    {
        $response = $this->data->customer($request->only('page', 'search', 'per_page'));
        
        apiResponseHandler($response, false);

        return response([
            'result' => $response->json('result.data'),
            'more' => $response->json('result.next_page_url') != null ? true : false,
            'page' => $response->json('result.current_page'),
        ]);
    }
}
