<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 12-Apr-23
 * Time: 13:27
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SettlementTransferController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $access_control = "SETTLEMENT_MASTER_VIEW";
            if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
                return $next($request);
            } else {
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => $access_control,
                ]));
            }
        });
    }

    public function index(Request $request) {
        $page = $request->get("page", 1);
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

        $per_page = $request->get("per_page", 10);
        if ($request->has("export_to_csv")) {
            $per_page = 99999999;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }
        $settlement_transfer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($settlement_transfer_response->is_ok) {
            $result = $settlement_transfer_response->result;

            $settlement_transfer_list = $result->data;

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Settlement Transfer List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'Partner Name',
                    'Settlement Date',
                    'Ref Code',
                    'Total Merchant Portion',
                    'Total Partner Portion',
                    'Total Transfer Amount',
                    'Type',
                    'Status',
                    'Settlement Master List',
                ];

                return H::getStreamCsv("Settlement Transfer List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv", $columns, $settlement_transfer_list, function($settlement_transfer) {
                    return [
                        @$settlement_transfer->partner->name,
                        @$settlement_transfer->settlement_date,
                        @$settlement_transfer->ref_code,
                        @number_format($settlement_transfer->total_merchant_portion, 2, ".", ""),
                        @number_format($settlement_transfer->total_fee_partner, 2, ".", ""),
                        @number_format($settlement_transfer->total_transfer, 2, ".", ""),
                        @$settlement_transfer->type,
                        @$settlement_transfer->status,
                        @collect($settlement_transfer->settlement_to_parking_masters)->pluck("ref_code")->implode(","),
                    ];
                });
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.settlement_transfers.list", [
                    "settlement_transfer_list" => $settlement_transfer_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_date,
                    "end_time" => $end_date,
                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_transfer_response);
        }
    }


    public function show(Request $request, $settlement_transfer_id) {
        $settlement_transfer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_transfer_id" => $settlement_transfer_id,
            ],
        ]);

        //dd($settlement_transfer_response);
        if ($settlement_transfer_response->is_ok) {
            $result = $settlement_transfer_response->result;

            return view("yukk_co.settlement_transfers.show", [
                "settlement_transfer" => $result,
            ]);
        } else if ($settlement_transfer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_transfer_response);
        }
    }

    public function action(Request $request, $settlement_transfer_id) {
        $access_control = "SETTLEMENT_MASTER.ACTION";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return $this->actionUnauthorized($access_control);
        }

        $validator = Validator::make($request->all(), [
            "status" => "required",
            "action" => "required",
        ]);
        $validator->validate();

        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_TRANSFER_ACTION_YUKK_CO, [
            "form_params" => [
                "settlement_transfer_id" => $settlement_transfer_id,
                "status" => $request->get("status"),
                "action" => $request->get("action"),
            ],
        ]);

        if (! $settlement_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }


        S::flashSuccess($settlement_master_response->status_message, true);
        return redirect(route("cms.yukk_co.settlement_transfer.show", $settlement_transfer_id));
    }

}
