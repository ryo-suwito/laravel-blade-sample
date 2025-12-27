<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;

class ManualTransferController extends Controller
{
    protected $transfers;

    public function __construct()
    {
        $this->transfers = API::instance('transaction_online', 'manual_transfer');
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = [];
        $data['date'] = $request->date;
        $data['settlement'] = true;
        $data['items'][0]['status'] = 'on';
        $data['items'][0]['customer_id'] = $request->entity_id;
        $data['items'][0]['customer_type'] = $request->customer_type;
        $data['items'][0]['date'] = $request->date;
        $data['items'][0]['type'] = $request->type;

        $response = $this->transfers->create($data);

        apiResponseHandler($response);

        return redirect()->route('yukk-co.transaction-online.settlements.index', ['date' => $data['date']])->with('success', 'Create transfer success!');
    }
}
