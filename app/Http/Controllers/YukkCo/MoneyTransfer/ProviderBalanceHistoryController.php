<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Services\MoneyTransfer\ProviderBalanceHistoryService;
use App\Services\MoneyTransfer\ProviderBalanceService;
use App\Services\MoneyTransfer\TransactionItemService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderBalanceHistoryController extends BaseController
{
    protected $service;
    protected $providerBalanceService;
    protected $transactionItemService;

    public function __construct()
    {
        $this->service = new ProviderBalanceHistoryService();
        $this->providerBalanceService = new ProviderBalanceService();
        $this->transactionItemService = new TransactionItemService();
    }

    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $providerSelected = $request->get("provider", "all");
        $filter = $request->get("filter", "all");
        $date_range_string = $request->get("date_range", null);
        $search = $request->get("search", null);
        $tag = $request->get("tag", null);

        $query_params = [
            "page" => $page,
            "filter" => $filter,
            "provider" => $providerSelected,
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

        $get_provider_balance_history_response = $this->service->paginated($query_params);

        if($get_provider_balance_history_response->status() && $get_provider_balance_history_response->json('status_code') == 200) {

            $get_provider_balance_summary_response = $this->providerBalanceService->summary($query_params);

            if($get_provider_balance_summary_response->status() && $get_provider_balance_summary_response->json('status_code') == 200) {
                return view('yukk_co.money_transfer.provider_balance_histories.index', [
                    'filters' => array_merge($query_params, [
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                    ]),
                    'search' => $search,
                    'current_page' => $get_provider_balance_history_response->json('result.current_page'),
                    'last_page' => $get_provider_balance_history_response->json('result.last_page'),
                    'total' => $get_provider_balance_history_response->json('result.total'),
                    'histories' => $get_provider_balance_history_response->json('result.data'),
                    'summary' => $get_provider_balance_summary_response->json('result'),
                ]); 
            } else {

                return $this->getErrorAction(
                    'Money Transfer - Provider Balances - Get Provider Balance Summary', 
                    $get_provider_balance_summary_response
                );
            }
        } else {

            return $this->getErrorAction(
                'Money Transfer - Provider Balance History - Get Provider Balance Histories', 
                $get_provider_balance_history_response
            );
        }
    }

    public function cashout(Request $request)
    {
        $query_params = [
            "provider_code" => $request->provider_code,
            "description" => $request->cashout_description,
            "created_by" => session()->get("user")->full_name,
            "amounts" => [
                "UNIQUE_CODE" => abs((int) $request->cashout_unique_code_non_format)*-1
            ],
        ];

        $response = $this->providerBalanceService->cashout($query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("Cashout was successful", true);

            return redirect()->back();
        } else {
            
            Log::error('Money Transfer - Provider Balance - Cashout', [
                'response' => $response
            ]);

            H::flashSuccess("Cashout failed", true);
        }
    }

    public function adjustment(Request $request)
    {
        if((int) $request->adjustment_unique_code_non_format == (int) $request->adjustment_last_unique_code && (int) $request->adjustment_balance_non_format == (int) $request->adjustment_last_balance) {
            H::flashFailed("No change in balances", true);

            return redirect()->back();
        }

        if((int) $request->adjustment_unique_code_non_format < 0 || (int) $request->adjustment_balance_non_format < 0) {
            H::flashFailed("Balance cannot negative", true);

            return redirect()->back();
        }

        $query_params = [
            "provider_code" => $request->provider_code,
            "description" => $request->adjustment_description,
            "created_by" => session()->get("user")->full_name,
            "amounts" => [],
        ];

        if((int) $request->adjustment_balance_non_format != (int) $request->adjustment_last_balance) {
            $query_params["amounts"]["PRIMARY"] = (int) $request->adjustment_balance_non_format - (int) $request->adjustment_last_balance;
        }

        if((int) $request->adjustment_unique_code_non_format != (int) $request->adjustment_last_unique_code) {
            $query_params["amounts"]["UNIQUE_CODE"] = (int) $request->adjustment_unique_code_non_format - (int) $request->adjustment_last_unique_code;
        }

        $response = $this->providerBalanceService->adjustment($query_params);

        if($response->status() && $response->json('status_code') != 200) {
            Log::error('Money Transfer - Provider Balance - Adjusment Balance', [
                'response' => $response
            ]);

            H::flashFailed("Adjusment Balance failed", true);
        }

        H::flashSuccess("Adjusment was successful", true);

        return redirect()->back();
    }
}
