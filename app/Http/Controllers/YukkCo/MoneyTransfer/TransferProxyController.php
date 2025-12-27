<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\MoneyTransfer\TransferProxy\Filter;
use App\Helpers\H;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransferProxyController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transfer_proxy');
    }

    public function index(Request $request) 
    {
        try {
            $filters = new Filter($request);

            $res = $this->service->get($filters->values());

            return view("yukk_co.money_transfer.transfer_proxy.index", [
                'trxs' => $res->json('result.data'),
                'paginator' => Paginator::fromResponse($res),
                'filters' => $filters->values(),
                'filterCounter' => $filters->counter(),
                'perPages' => $this->pageOptions,
                'total' => $res->json('result.total')
            ]);
        } catch (Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }

            if ($e->response->status() == 422) {
                toast('error', $e->response->json('status_message'));
                return redirect()->back();
            }

            Log::error($e, [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            toast('error', 'There is something wrong with our server. Please try again later.');
            return redirect()->back();
        }
    }

    public function show(Request $request, $code) 
    {
        try {            
            $res = $this->service->find($code);

            return view("yukk_co.money_transfer.transfer_proxy.show", [
                'trx' => $res->json('result')
            ]);
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
            return redirect()->back();
        }
    }

    public function update(Request $request, $id) 
    {
        try {
            if ($request->get('success', 0) == 1) {
                $this->service->markAsSuccess($id);
            } else if($request->get('retry', 0) == 1){
                $this->service->retry($id);
            } else {
                $this->service->update($id);
            }

            toast('success', 'Update in progress, please wait!');
            
            return redirect()->back();
        } catch (Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }

                if ($e->response->status() == 429) {
                    toast('error', 'Please wait a minute and try again');
                    return redirect()->back();
                }

                if ($e->response->status() == 403) {
                    toast('error', $e->response->json('data.message'));
                    return redirect()->back();
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            toast('error', 'There is something wrong with our server. Please try again later.');
            return redirect()->back();
        }
    }
}
