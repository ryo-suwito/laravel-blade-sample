<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Services\MoneyTransfer\ProviderDepositService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProviderDepositController extends BaseController
{
    const MASKING_DEFAULT_STATUS = [
        'ONGOING.TOP_UP' => 'PENDING',
        'FAILED.TOP_UP' => 'FAILED',
        'ONGOING.TRANSFER_BCA' => 'PENDING',
        'FAILED.TRANSFER_BCA' => 'FAILED',
        'SUCCESS.TRANSFER_BCA' => 'SUCCESS',
        'ONGOING.CHECK_TOP_UP' => 'PENDING',
        'PENDING.CHECK_TOP_UP' => 'PENDING',
    ];

    protected $service;

    public function __construct()
    {
        $this->service = new ProviderDepositService();
    }

    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $tab = $request->get("tab", "all");
        $date_range_string = $request->get("date_range", null);
        $search = $request->get("search", null);
        $tag = $request->get("tag", null);

        $query_params = [
            "page" => $page,
            "status" => $tab,
            "search" => $search,
            "tag" => $tag,
        ];

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->startOfDay();
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->subDays(7)->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d")." 00:00:00";
            $query_params["end_date"] = $end_date->format("Y-m-d")." 23:59:59.999999";
        }

        $get_provider_deposits_response = $this->service->paginated($query_params);

        if($get_provider_deposits_response->status() && $get_provider_deposits_response->json('status_code') == 200) {

            $status_counter_response = $this->service->statusCounter();

            if($status_counter_response->status() && $status_counter_response->json('status_code') == 200) {

                $result = $get_provider_deposits_response->json('result');

                return view("yukk_co.money_transfer.provider_deposits.index", [
                    "tab" => $tab,
                    "start_time" => $start_date,
                    "end_time" => $end_date,
                    "search" => $search,
                    "tag" => $tag,
                    "current_page" => $result['current_page'],
                    "last_page" => $result['last_page'],
                    "total" => $result['total'],
                    "deposits" => $result['data'],
                    "status_counter" => $status_counter_response->json('result'),
                    "masking" => static::MASKING_DEFAULT_STATUS
                ]);
            } else {

                return $this->getErrorAction(
                    'Money Transfer - Top Up Balance - Get Status Counter',
                    $status_counter_response
                );
            }

        } else {

            return $this->getErrorAction(
                'Money Transfer - Top Up Balance - Get Provider Deposits',
                $get_provider_deposits_response
            );
        }
    }

    public function detail($id)
    {
        $response = $this->service->find($id);

        if($response->status() && $response->json('status_code') == 200) {

            return view("yukk_co.money_transfer.provider_deposits.detail", [
                'deposit' => $response->json('result'),
            ]);

        } else {

            return $this->getErrorAction(
                'Money Transfer - Top Up Balance - Get Detail Provider Deposit',
                $response
            );
        }

    }

    public function retry($id)
    {
        $response = $this->service->retry($id);

        if($response->status() && $response->json('status_code') == 200) {

            H::flashSuccess('Retry/Update in progress, please wait!', true);

            return redirect()->back();

        } else if($response->status() == 429) {
            H::flashFailed('Please try again in a minute', true);

            return redirect()->back();
        } else {
            return $this->getErrorAction(
                'Money Transfer - Top Up Balance - Do Retry Provider Deposit',
                $response
            );
        }
    }

    public function markSuccess($id)
    {
        $response = $this->service->markSuccess($id);

        if($response->status() && $response->json('status_code') == 200) {

            H::flashSuccess('Mark as success in progress, please wait!', true);

            return redirect()->back();

        } else if($response->status() == 429) {
            H::flashFailed('Please try again in a minute', true);

            return redirect()->back();
        } else {
            return $this->getErrorAction(
                'Money Transfer - Top Up Balance - Do Mark Success Provider Deposit',
                $response
            );
        }
    }
}
