<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    protected $settlements;

    public function __construct()
    {
        $this->settlements = API::instance('transaction_online', 'settlement');
    }

    public function index(Request $request)
    {
        $date = $request->date ? $request->date : date("Y-m-d");

        $response = $this->settlements->get([
            'date' => $date,
        ]);

        apiResponseHandler($response, false);

        return view('yukk_co.transaction_online.settlement.index',[
            'settlements' => $response->json('result'),
            'date' => $date,
        ]);
    }
}
