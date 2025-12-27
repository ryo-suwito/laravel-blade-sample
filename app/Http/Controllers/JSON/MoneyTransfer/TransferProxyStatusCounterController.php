<?php

namespace App\Http\Controllers\JSON\MoneyTransfer;

use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransferProxyStatusCounterController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'transfer_proxy');
    }

    public function __invoke()
    {
        try {
            $response = $this->service->statusCounter();
            
            return response()->json($response->json());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    return response()->json([
                        'message' => 'Unauthorization.'
                    ], 401);
                }
            }

            return response()->json([
                'message' => 'Something went wrong'
            ], 503);
        }

    }
}
