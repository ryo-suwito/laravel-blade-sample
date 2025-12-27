<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 30-Nov-21
 * Time: 11:10
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SettlementPgMasterController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_MASTER_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "SETTLEMENT_MASTER_VIEW",
                ]));
            }
        });
    }
    public function index(Request $request) {
        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $per_page = $request->get("per_page", 10);
        
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
            $start_date = Carbon::now()->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        if ($request->has("export_to_csv")) {
            $per_page = 999999;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }
        $settlement_pg_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($settlement_pg_master_response->is_ok) {
            $result = $settlement_pg_master_response->result;

            $settlement_pg_master_list = $result->data;

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Settlement PG List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'BeneficiaryName',
                    'BeneficiaryBankType',
                    'PartnerName',
                    'PartnerBankType',
                    'SettlementDate',
                    'RefCode',
                    'Total_GrandTotalTrx',
                    'TotalMDRProvider',
                    'TotalMDRExternal',
                    'MerchantPortion',
                    'PartnerPortion',
                    'YUKKPortion',
                    'Status',
                ];

                $callback = function() use ($settlement_pg_master_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($settlement_pg_master_list as $settlement_pg_master) {
                        fputcsv($file, [
                            @$settlement_pg_master->customer->name,
                            @$settlement_pg_master->customer->bank_type,
                            @$settlement_pg_master->partner->name,
                            @$settlement_pg_master->partner->bank_type,
                            @$settlement_pg_master->settlement_date,
                            @$settlement_pg_master->ref_code,
                            @number_format($settlement_pg_master->total_grand_total, 2, ".", ""),
                            @number_format($settlement_pg_master->total_mdr_internal, 2, ".", ""),
                            @number_format($settlement_pg_master->total_mdr_external, 2, ".", ""),
                            @number_format($settlement_pg_master->total_merchant_portion, 2, ".", ""),
                            @number_format($settlement_pg_master->total_fee_partner, 2, ".", ""),
                            @number_format(($settlement_pg_master->total_mdr_external - $settlement_pg_master->total_mdr_internal), 2, ".", ""),
                            @$settlement_pg_master->status,
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.settlement_pg_masters.list", [
                    "settlement_pg_master_list" => $settlement_pg_master_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_date,
                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],
                    "end_time" => $end_date,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_pg_master_response);
        }
    }


    public function show(Request $request, $settlement_pg_master_id) {
        $settlement_pg_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_pg_master_id" => $settlement_pg_master_id,
            ],
        ]);

        if ($settlement_pg_master_response->is_ok) {
            $result = $settlement_pg_master_response->result;

            return view("yukk_co.settlement_pg_masters.show", [
                "settlement_pg_master" => $result,
            ]);
        } else if ($settlement_pg_master_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_pg_master_response);
        }
    }

    public function listSourceOfFund(Request $request) {
        $date_range_string = $request->get("date_range", null);

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
            $start_date = Carbon::now()->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        $query_params = [];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }
        $settlement_pg_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_PG_SETTLEMENT_MASTER_LIST_SOURCE_OF_FUND_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($settlement_pg_master_response->is_ok) {
            $result = $settlement_pg_master_response->result;

            return view("yukk_co.settlement_pg_masters.list_provider", [
                "source_of_fund_list" => $result,
                "start_time" => $start_date,
                "end_time" => $end_date,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_pg_master_response);
        }
    }



}