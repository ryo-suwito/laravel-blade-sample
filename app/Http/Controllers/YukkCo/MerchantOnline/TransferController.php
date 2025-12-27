<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use App\Services\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransferController extends Controller
{
    protected $transfers;

    public function __construct()
    {
        $this->transfers = API::instance('transaction_online', 'transfer');
    }

    public function index(Request $request)
    {
        $date = Carbon::parse($request->get('date', date('Y-m-d')));

        $filters = [
            'date' => [
                '$between' => [
                    $date->startOfDay()->toDateTimeString(),
                    $date->endOfDay()->toDateTimeString(),
                ],
            ],
        ];

        $response = $this->transfers->paginate([
            'page' => $request->get('page', 1),
            'filters' => $filters
        ]);

        apiResponseHandler($response, false);

        return view('yukk_co.transaction_online.transfer.index',[
            'transfers' => Paginator::fromResponse($response)->appends(compact('date')),
            'date' => $date->format('Y-m-d'),
        ]);
    }

    public function store(Request $request)
    {
        $data = [];
        $data['date'] = $request->get('date');
        $data['settlement'] = true;

        foreach ($request->transfer as $transfer) {
            if ( !array_key_exists('status',$transfer) ) {
                continue;
            }

            if ($transfer['status'] != 'on') {
                continue;
            }

            $data['items'][] = $transfer;
        }


        $response = $this->transfers->post($data);

        apiResponseHandler($response);

        return redirect()->route('yukk-co.transaction-online.settlements.index', ['date' => $data['date']])->with('success', 'Create transfer success!');
    }

    public function show($id)
    {
        $transferDetails = Http::get(env('API_TRANSACTION_ONLINE') . '/transfers/'.$id)->body();

        return view('yukk_co.transaction_online.transfer.show',[
            'transfer_details' => json_decode($transferDetails)->result,
        ]);
    }
}
