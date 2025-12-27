<?php

namespace App\Http\Controllers\JSON\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;

class PaymentChannelController extends Controller
{
    protected $paymentChannel;

    public function __construct()
    {
        $this->paymentChannel = API::instance('payment_gateway', 'payment_channel');
    }
    
    public function index(Request $request, $partner_id)
    {
        $response = $this->paymentChannel->all('provider', $request->get('provider'));

        apiResponseHandler($response, false);

        return response()->json($response->json('result'));
    }
}
