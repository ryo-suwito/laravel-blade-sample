<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionItemWebhookSendController extends BaseController
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
            $this->service->webhookSend($code);

            toast('success', 'Resend webhook in progress, please wait!');
            return redirect()->back();
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }

                if (in_array($e->response->status(), [400, 403, 404])) {
                    toast('error', $e->response->json('status_message'));
                    return redirect()->back();
                }

                if ($e->response->status() == 429) {
                    toast('error', 'Please wait in a minute and try again');
                    return redirect()->back();
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
