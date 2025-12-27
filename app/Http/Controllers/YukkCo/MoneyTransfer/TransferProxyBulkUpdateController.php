<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransferProxyBulkUpdateController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transfer_proxy');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $providerCode = $request->get('provider', null);
            $selectedCodes = $request->get('selectedCodes', null);

            if ($request->get('success', 0) == 1) {
                $this->service->bulkMarkAsSuccess($providerCode, $selectedCodes);
            } elseif ($request->get('failed', 0) == 1) {
                $this->service->bulkMarkAsFailed($providerCode, $selectedCodes);
            } else {
                $request->get('retry', 0) == 1
                    ? $this->service->bulkRetry($providerCode, $selectedCodes)
                    : $this->service->bulkUpdate($providerCode, $selectedCodes);
            }

            toast('success', 'Update in progress, please wait!');
            return redirect()->back();
        } catch (Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            toast('error', 'There is something wrong with our server. Please try again later.');
            return redirect('dashboard');
        }
    }
}
