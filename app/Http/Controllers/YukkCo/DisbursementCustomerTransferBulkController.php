<?php
/**
 * Created by PhpStorm.
 * User: loren
 * Date: 15-Sep-23
 * Time: 00:22
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisbursementCustomerTransferBulkController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_CUSTOMER_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "DISBURSEMENT_CUSTOMER_VIEW",
                ]));
            }
        });
    }

    public function index(Request $request) {
        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $ref_code = $request->get("ref_code", null);

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

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_excel") ? 9999999 : 100,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }

        if ($ref_code) {
            $query_params["ref_code"] = $ref_code;
        }

        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if (! $disbursement_customer_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }

        $result = $disbursement_customer_response->result;

        $disbursement_customer_transfer_bulk_list = $result->data;

        if ($request->has("export_to_excel")) {
            $data_arr = [["Disbursement Date", "Ref Code", "Total Transfer", "Type", "Status", "Source Account Number", "Destination Account Number", "Corp ID Code", ]];

            foreach ($disbursement_customer_transfer_bulk_list as $disbursement_customer_transfer_bulk) {
                $data_arr[] = [
                    @$disbursement_customer_transfer_bulk->disbursement_date,
                    @$disbursement_customer_transfer_bulk->ref_code,
                    @$disbursement_customer_transfer_bulk->total_transfer + $disbursement_customer_transfer_bulk->unique_code,
                    @$disbursement_customer_transfer_bulk->type,
                    @$disbursement_customer_transfer_bulk->status,

                    @$disbursement_customer_transfer_bulk->source_account_number,
                    @$disbursement_customer_transfer_bulk->destination_account_number,
                    @$disbursement_customer_transfer_bulk->source_corp_id,
                ];
            }
            H::getStreamExcel("Disbursement Customer Transfer Bulk " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y"), $data_arr);
            die();
        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("yukk_co.disbursement_customer_transfer_bulk.list", [
                "disbursement_customer_transfer_bulk_list" => $disbursement_customer_transfer_bulk_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_time" => $start_date,
                "end_time" => $end_date,

                "ref_code" => $ref_code,
            ]);
        }
    }

    public function show(Request $request, $disbursement_customer_transfer_bulk_id) {
        $disbursement_customer_transfer_bulk_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_ITEM_YUKK_CO, [
            "form_params" => [
                "disbursement_customer_transfer_bulk_id" => $disbursement_customer_transfer_bulk_id,
            ],
        ]);

        if (@ $disbursement_customer_transfer_bulk_response->status_code == 7014) {
            return abort(404);
        }

        if (! $disbursement_customer_transfer_bulk_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_transfer_bulk_response);
        }

        $result = $disbursement_customer_transfer_bulk_response->result;

        return view("yukk_co.disbursement_customer_transfer_bulk.show", [
            "disbursement_customer_transfer_bulk" => $result,
        ]);
    }

    public function exportExcel(Request $request, $disbursement_customer_transfer_bulk_id) {
        $disbursement_customer_transfer_bulk_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_ITEM_YUKK_CO, [
            "form_params" => [
                "disbursement_customer_transfer_bulk_id" => $disbursement_customer_transfer_bulk_id,
            ],
        ]);

        if (@ $disbursement_customer_transfer_bulk_response->status_code == 7014) {
            return abort(404);
        }

        if (! $disbursement_customer_transfer_bulk_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_transfer_bulk_response);
        }

        $result = $disbursement_customer_transfer_bulk_response->result;

        $data_arr = [];
        $data_arr[] = ["Ref Code", $result->ref_code,];
        $data_arr[] = ["Disbursement Date", $result->disbursement_date,];
        $data_arr[] = ["Source Account Number", $result->source_account_number,];
        $data_arr[] = ["Destination Account Number", $result->destination_account_number,];
        $data_arr[] = ["Total Merchant Portion", $result->total_merchant_portion,];
        $data_arr[] = ["Total Disbursement Fee", $result->total_disbursement_fee,];
        $data_arr[] = ["Total Rounding", $result->total_rounding,];
        $data_arr[] = ["Unique Code", $result->unique_code,];
        $data_arr[] = ["Total Transfer", $result->total_transfer,];
        $data_arr[] = ["Type", $result->type,];
        $data_arr[] = ["Status", $result->status,];
        $data_arr[] = [null];
        $data_arr[] = ["BeneficiaryID", "BeneficiaryName", "PartnerID", "PartnerName", "RefCode", "DisbursementDate", "TotalMerchantPortion", "DisbursementFee", "Rounding", "TotalDisbursement", "TransferUsing", "Status"];

        foreach ($result->disbursement_customer_transfer_list as $index => $disbursement_customer_transfer) {
            $data_arr[] = [
                @$disbursement_customer_transfer->customer_id,
                @$disbursement_customer_transfer->customer->name,
                @$disbursement_customer_transfer->partner_id,
                @$disbursement_customer_transfer->partner->name,
                @$disbursement_customer_transfer->ref_code,
                @$disbursement_customer_transfer->disbursement_date,
                @$disbursement_customer_transfer->total_merchant_portion,
                @$disbursement_customer_transfer->disbursement_fee,
                @$disbursement_customer_transfer->rounding,
                @$disbursement_customer_transfer->total_disbursement,
                @$disbursement_customer_transfer->transfer_using,
                @$disbursement_customer_transfer->status,
            ];
        }


        H::getStreamExcel("Disbursement Customer Transfer Bulk " . $result->ref_code, $data_arr);
        die();
    }

    public function action(Request $request, $disbursement_customer_id) {
        $access_control = "DISBURSEMENT_CUSTOMER_TRANSFER.ACTION";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "status" => "required",
            "action" => "required",
            "type" => "required",
        ]);
        $validator->validate();

        $form_params = [
            "disbursement_customer_transfer_bulk_id" => $disbursement_customer_id,
            "status" => $request->get("status"),
            "action" => $request->get("action"),
            "type" => $request->get("type"),
        ];
        $disbursement_customer_transfer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_BULK_ACTION_YUKK_CO, [
            "form_params" => $form_params,
        ]);

        if (! $disbursement_customer_transfer_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_transfer_response);
        }

        S::flashSuccess($disbursement_customer_transfer_response->status_message, true);
        return redirect(route("cms.yukk_co.disbursement_customer_transfer_bulk.item", $disbursement_customer_transfer_response->result->id));
    }

}
