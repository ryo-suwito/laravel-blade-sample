<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use App\Services\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct()
    {
        $this->transaction = API::instance('transaction_online', 'transaction');
    }

    public function index(Request $request)
    {
        $type = $request->get('type') == 'YUKK' ? ['$eq' => 'YUKK'] : ['$ne' => 'YUKK'];

        $action = $request->get('action') ?? null;
        $action = $request->get('action') == null ? ['$null' =>true] : ['$notNull'=>true];

        $filters = [
            'customer_id' => [
                '$eq' => $request->get('customer_id'),
            ],
            'payment_method' => $type,
            'action' => $action,
            'fulfillment_status' => [
                '$eq' => $request->get('fulfillment_status'),
            ],
            'transaction_time' => [
                '$lt' =>
                    Carbon::parse($request->get('date', date('Y-m-d')))
                        ->endOfDay()
                        ->toDateTimeString()
            ],
            'status' => ['$eq' => $request->get('status')],
            'item_id' => ['$eq' => $request->get('item_id')],
        ];

        $response = $this->transaction->paginate([
            'page' => $request->get('page', 1),
            'filters' => $filters,
        ]);

        apiResponseHandler($response, false);

        return view('yukk_co.transaction_online.transaction.index',[
            'transactions' => Paginator::fromResponse($response)->appends($request->only(['customer_id', 'payment_method', 'fulfillment_status', 'transaction_time'])),
        ]);
    }
}
