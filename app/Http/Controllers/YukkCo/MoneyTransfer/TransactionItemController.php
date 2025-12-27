<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionItemController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transaction_item');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $code)
    {
        try {
            $response = $this->service->show($code);

            return view('yukk_co.money_transfer.transaction_item.show', [
                'item' => $response->json('result'),
                'clickFrom' => $request->get('from', 'batch')
            ]);
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            H::flashFailed(__('There is something wrong with our server. Please try again later.'), true);
            return redirect('dashboard');
        }
    }
}
