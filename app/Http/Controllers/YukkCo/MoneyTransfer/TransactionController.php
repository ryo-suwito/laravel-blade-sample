<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\MoneyTransfer\Transaction\Filter;
use App\Helpers\H;
use App\Services\API;
use App\Services\MoneyTransfer\TransactionService;
use App\Services\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionController extends BaseController
{
    protected $service;

    protected $retryTimeLimit = 300;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transaction');
    }

    public function index(Request $request)
    {
        try {
            $filters = new Filter($request);

            $response = $this->service->paginated($filters->values());
            $statusCounterResponse = $this->service->statusCounter();

            return view('yukk_co.money_transfer.transactions.index', [
                'filters' => $filters->values(),
                'filterCounter' => $filters->counter(),
                'paginator' => Paginator::fromResponse($response),
                'perPages' => $this->pageOptions,
                'retryTimeLimit' => $this->retryTimeLimit,
                'statusCounter' => $statusCounterResponse->json('result'),
                'total' => $response->json('result.total'),
                'transactions' => $response->json('result.data'),
            ]);
        } catch (Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }

                if ($e->response->status() == 422) {
                    toast('error', $e->response->json('status_message'));
                    return redirect()->back();
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

    public function detail($id)
    {
        $get_transactions_response = $this->service->find($id);

        if($get_transactions_response->status() && $get_transactions_response->json('status_code') == 200) {
            $transaction = $get_transactions_response->json('result');

            $lastUpdated = Carbon::parse($transaction['updated_at']);            
            $now = Carbon::now('Asia/Jakarta');
            
            $diff = $lastUpdated->diffInSeconds($now, false);

            $transaction['canRetry'] = $diff > $this->retryTimeLimit;

            return view('yukk_co.money_transfer.transactions.detail', [
                'transaction' => $transaction,
            ]);

        } else {

            return $this->getErrorAction(
                'Money Transfer - Transaction - Get Detail Transaction',
                $get_transactions_response
            );
        }

    }

    public function update(Request $request, $id)
    {
        if ($request->get('success', 0) == 1) {
            $response = $this->service->markAsSuccess($id);
        } elseif ($request->get('failed', 0) == 1) {
            $response = $this->service->markAsFailed($id);
        } else {
            $response = $request->get('retry', 0) == 1
                ? $this->service->retry($id)
                : $this->service->update($id);
        }

        if($response->status() && $response->json('status_code') == 200) {

            H::flashSuccess('Update in progress, please wait!', true);

            return redirect()->back();

        } else if($response->status() == 429) {
            H::flashFailed($response->json('status_message') ?? 'Please try again in a minute', true);

            return redirect()->back();
        } else {

            return $this->getErrorAction(
                'Money Transfer - Transaction - Update',
                $response
            );
        }
    }
}
