<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\MoneyTransfer\TransferProxy\Filter as ProxyFilter;
use App\Actions\MoneyTransfer\TransactionGroup\Filter;
use App\Actions\MoneyTransfer\TransactionGroup\ItemsFilter;
use App\Helpers\H;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;;

class TransactionGroupController extends BaseController
{
    protected $service;
    protected $transferProxyService;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transaction_group');
        $this->transferProxyService = API::instance('money_transfer', 'transfer_proxy');
    }

    public function index(Request $request)
    {
        try {
            $filters = new Filter($request);

            $response = $this->service->paginate($filters->values());

            $result = $response->json('result');

            return view('yukk_co.money_transfer.group.index', [
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
            $transferProxyfilters = new ProxyFilter($request, [
                'prefix' => 'proxy_'
            ]);

            $response = $this->service->get($code);

            $itemResponse = $this->service->items($code, $filters->values());

            $proxyResponse = $this->transferProxyService->get(
                array_merge($transferProxyfilters->values(), [
                    'group_id' => $response->json('result.id'),
                ])
            );

            return view('yukk_co.money_transfer.group.show', [
                'group' => $response->json('result'),
                'items' => $itemResponse->json('result.data'),
                'paginator' => Paginator::fromResponse($itemResponse),
                'filters' => $filters->values(),
                'filterCounter' => $filters->counter(),
                'perPages' => $this->pageOptions,
                'proxies' => $proxyResponse->json('result.data'),
                'proxyPaginator' => Paginator::fromResponse($proxyResponse, [
                    'pageName' => 'proxy_page'
                ]),
                'proxyFilters' => $transferProxyfilters->values(),
                'proxyFilterCounter' => $transferProxyfilters->counter(),
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
