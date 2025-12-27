<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;

class RetryTransferItemController extends Controller
{
    protected $items;

    public function __construct()
    {
        $this->items = API::instance('transaction_online', 'transfer_item');
    }

    public function __invoke(Request $request, $id){
        $response = $this->items->retry($id);

        apiResponseHandler($response);

        return redirect()->route('yukk-co.transaction-online.transfer-items.index', ['transfer_id' => $response->json('result')['transfer_id']])->with('success', 'Retry transfer item success!');
    }
}
