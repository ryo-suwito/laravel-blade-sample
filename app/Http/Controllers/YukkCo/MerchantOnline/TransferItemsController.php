<?php

namespace App\Http\Controllers\YukkCo\MerchantOnline;

use App\Http\Controllers\Controller;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransferItemsController extends Controller
{
    protected $items;

    public function __construct()
    {
        $this->items = API::instance('transaction_online', 'transfer_item');
    }

    public function index(Request $request)
    {
        $filters = [
            'transfer_id' => [
                '$eq' => $request->get('transfer_id'),
            ],
        ];

        $response = $this->items->paginate([
            'page' => $request->get('page', 1),
            'filters' => $filters,
        ]);

        apiResponseHandler($response, false);

        return view('yukk_co.transaction_online.transfer_item.index',[
            'items' => Paginator::fromResponse($response)->appends($request->only('transfer_id')),
        ]);
    }
}
