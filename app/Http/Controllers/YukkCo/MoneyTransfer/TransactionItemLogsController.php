<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Constants\MoneyTransfer\ProviderConstant as Provider;
use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Throwable;

class TransactionItemLogsController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transaction');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $code = $request->get('code');
        $provider = $request->get('provider');
        $type = $request->get('type', 'response');
        $isProxy = Route::currentRouteName() == 'money_transfer.transfer_proxies.logs';
        
        $provider = $isProxy && $provider == Provider::YUKK ? Provider::BCA : $provider; 

        try {
            $method = $type == 'response' ? 'getLogs' : 'getErrorLogs';

            $response = $this->service->{$method}($code, $provider);

            return view('yukk_co.money_transfer.log.index', [
                'code' => $code,
                'logs' => $response->json('result'),
                'type' => $type,
            ]);
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }

                if ($e->response->status() == 404) {
                    return view('yukk_co.money_transfer.log.index', [
                        'code' => $code,
                        'logs' => [],
                        'type' => $type,
                    ]);
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
            ]);

            toast('error', __('There is something wrong with our server. Please try again later.'));
            return redirect('dashboard');
        }
        
    }
}
