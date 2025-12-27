<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SettingController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = API::instance('money_transfer', 'setting');
    }

    public function index()
    {
        try {
            $settings = $this->service->all();
            
            return view('yukk_co.money_transfer.settings.index', [
                'settings' => $settings->json('result')
            ]);
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }
            
            Log::error($e, [
                'class' => __CLASS__,
                'method' => __METHOD__,
            ]);

            H::flashFailed(__('There is something wrong with our server. Please try again later.'), true);
            return redirect()->back();
        }

    }

    public function update(Request $request)
    {
        try {
            $this->service->update([
                'settings' => $request->get('settings', [])
            ]);

            H::flashSuccess('Success update settings!', true);
            return redirect()->back();
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
                'method' => __METHOD__,
            ]); 

            H::flashFailed(__('There is something wrong with our server. Please try again later.'), true);
            return redirect()->back();
        }
    }
}
