<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\MoneyTransfer\TransactionBatch\Filter;
use App\Actions\MoneyTransfer\TransactionBatch\ItemsFilter;
use App\Helpers\H;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TransactionBatchController extends BaseController
{
    protected $service;
    protected $providerService;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transaction_batch');
        $this->providerService = API::instance('money_transfer', 'provider');
    }

    public function index(Request $request)
    {
        try {
            $filters = new Filter($request);

            $response = $this->service->paginate($filters->values());

            $result = $response->json('result');

            return view('yukk_co.money_transfer.batch.index', [
                'transactions' => $result['data'],
                'paginator' => Paginator::fromResponse($response),
                'filters' => $filters->values(),
                'filterCounter' => $filters->counter(),
                'perPages' => $this->pageOptions,
                'total' => $result['total'],
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

    public function show(Request $request, $code)
    {
        try {
            $filters = new ItemsFilter($request);

            $response = $this->service->get($code);

            $itemResponse = $this->service->items($code, $filters->values());

            $providerResponse = $this->providerService->list();

            $itemResult = $itemResponse->json('result');

            return view('yukk_co.money_transfer.batch.show', [
                'transaction' => $response->json('result'),
                'items' => $itemResult['data'],
                'paginator' => Paginator::fromResponse($itemResponse),
                'filters' => $filters->values(),
                'filterCounter' => $filters->counter(),
                'perPages' => $this->pageOptions,
                'transactionTotal' => $itemResult['total'],
                'providers' => $providerResponse->json('result'),
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

    public function export(Request $request, $code)
    {
        try {
            $filters = new ItemsFilter($request);

            $response = $this->service->items($code, $filters->values());

            $contents = base64_decode($response->json('result.base64_contents'));

            $filename = $response->json('result.filename');

            Storage::put("tmp/$filename", $contents);

            return response()->download(Storage::path("tmp/$filename"))->deleteFileAfterSend();
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
