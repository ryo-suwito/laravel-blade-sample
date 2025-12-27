<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Request;

class TransferItemTransactionController extends Controller
{
    protected $items;

    public function __construct()
    {
        $this->items = API::instance('transaction_online', 'transfer_item');
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $response = $this->items->transactions($id);

        apiResponseHandler($response);

        return view('yukk_co.transaction_online.transaction.index',[
            'transactions' => Paginator::fromResponse($response),
        ]);
    }
}
