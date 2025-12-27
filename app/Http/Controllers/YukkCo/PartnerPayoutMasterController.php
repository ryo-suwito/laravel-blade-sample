<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 12-Dec-22
 * Time: 17:19
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerPayoutMasterController extends BaseController {

    public function index(Request $request) {
        $access_control = "PG_INVOICE.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $date_range_string = $request->get("created_at", null);
        $partner_id = $request->get("partner_id", -1);

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->startOfDay();
            }
            try {
                $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_time = Carbon::now()->endOfDay();
            }
        } else {
            $start_time = Carbon::now()->startOfDay();
            $end_time = Carbon::now()->endOfDay();
        }

        $per_page = 10;
        if ($request->has("export_to_csv")) {
            $per_page = 100000;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }

        if ($request->get("partner_id", -1) != -1) {
            $query_params['partner_id'] = $request->get("partner_id", -1);
        }

        $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_LIST_YUKK_CO, []);
        $partner_list = collect([]);
        if ($partner_response->is_ok) {
            $partner_list = $partner_response->result;
        }

        $partner_payout_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        //dd($partner_payout_master_response);
        if (! $partner_payout_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_response);
        }


        $result = $partner_payout_master_response->result;

        $partner_payout_master_list = $result->data;

        if ($request->has("export_to_csv")) {
            $file_name = "Partner Payout " . $start_time->format("d-M-Y") . " - " . $end_time->format("d-M-Y");
            $headers = [
                "Partner Name",
                "Ref Code",
                "Count Invoice All",
                "Count Invoice Done",
                "Sum Fee Partner Fixed",
                "Sum Fee Partner Percentage",
                "Sum Fee Partner Fixed (DONE)",
                "Sum Fee Partner Percentage (DONE)",
                "Created At",
                "Updated At",
                "Status",
                "Export At",
            ];
            $export_at = Carbon::now()->format("Y-m-d H:i:s");
            return H::getStreamCsv($file_name, $headers, $partner_payout_master_list, function($partner_payout_master) use($export_at) {
                return [
                    @$partner_payout_master->partner->name,
                    @$partner_payout_master->ref_code,
                    @$partner_payout_master->count_all_invoice,
                    @$partner_payout_master->count_done_invoice,
                    @number_format($partner_payout_master->sum_fee_partner_fixed, 2, ",", ""),
                    @number_format($partner_payout_master->sum_fee_partner_percentage, 2, ",", ""),
                    @number_format($partner_payout_master->sum_done_fee_partner_fixed, 2, ",", ""),
                    @number_format($partner_payout_master->sum_done_fee_partner_percentage, 2, ",", ""),
                    @$partner_payout_master->created_at,
                    @$partner_payout_master->updated_at,
                    @$partner_payout_master->status,
                    @$export_at,
                ];
            });
        }
        $current_page = $result->current_page;
        $last_page = $result->last_page;

        return view("yukk_co.partner_payout_master.list", [
            "partner_payout_master_list" => $partner_payout_master_list,
            "current_page" => $current_page,
            "last_page" => $last_page,
            "start_time" => $start_time,
            "end_time" => $end_time,

            "partner_list" => $partner_list,
            "partner_id" => $partner_id,
        ]);
    }

    public function show(Request $request, $partner_payout_master_id) {
        $access_control = "PG_INVOICE.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_payout_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_ITEM_YUKK_CO, [
            "query" => [
                "partner_payout_master_id" => $partner_payout_master_id,
            ],
        ]);

        if (! $partner_payout_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_response);
        }

        $data = [
            "partner_payout_master" => $partner_payout_master_response->result,
        ];

        //dd($data);

        return view("yukk_co.partner_payout_master.show", $data);
    }

    public function createSearch(Request $request) {
        $access_control = "PG_INVOICE.CREATE_PAYOUT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_id = $request->get("partner_id", null);

        $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_LIST_YUKK_CO, []);
        $partner_list = collect([]);
        if ($partner_response->is_ok) {
            $partner_list = $partner_response->result;
        }

        $data = [
            "partner_list" => $partner_list,
            "partner_id" => $partner_id,
        ];

        if ($partner_id) {
            $query_params = [];
            if ($partner_id != -1) {
                $query_params = [
                    "partner_id" => $partner_id,
                ];
            }

            $partner_payout_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_PREVIEW_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            $data['partner_payout_master_list'] = $partner_payout_master_response->result;

            if (! $partner_payout_master_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_response);
            }
        }

        return view("yukk_co.partner_payout_master.create_search", $data);
    }

    public function createPartner(Request $request, $partner_id) {
        $access_control = "PG_INVOICE.CREATE_PAYOUT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_payout_master_preview_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_PREVIEW_DETAIL_YUKK_CO, [
            "query" => [
                "partner_id" => $partner_id,
            ],
        ]);

        if (! $partner_payout_master_preview_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_preview_response);
        }

        $data = [
            "partner" => $partner_payout_master_preview_response->result->partner,
            "customer_invoice_master_list" => $partner_payout_master_preview_response->result->customer_invoice_master_list,
            "sum_fee_partner" => $partner_payout_master_preview_response->result->sum_fee_partner_fixed + $partner_payout_master_preview_response->result->sum_fee_partner_percentage,
            "count_all_invoice" => $partner_payout_master_preview_response->result->count_all_invoice,
        ];

        return view("yukk_co.partner_payout_master.create_partner", $data);
    }

    public function createPartnerPayoutMaster(Request $request, $partner_id) {
        $access_control = "PG_INVOICE.CREATE_PAYOUT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_payout_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_CREATE_YUKK_CO, [
            "query" => [
                "partner_id" => $partner_id,
            ],
        ]);

        if (! $partner_payout_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_response);
        }

        S::flashSuccess($partner_payout_master_response->status_message, true);
        return redirect(route("cms.yukk_co.partner_payout_master.show", $partner_payout_master_response->result->id));
    }

    public function markAsPaid(Request $request) {
        $access_control = "PG_INVOICE.CREATE_PAYOUT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "journal_date" => "required|date_format:d-M-Y",
        ]);
        if ($validator->fails()) {
            S::flashFailed($validator->errors()->first(), true);
            return back()->withInput();
        }

        $journal_date = Carbon::parse($request->get("journal_date"));

        $partner_payout_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_PAYOUT_MASTER_MARK_AS_PAID_YUKK_CO, [
            "form_params" => [
                "partner_payout_master_id" => $request->get("partner_payout_master_id"),
                "partner_payout_detail_ids" => $request->get("partner_payout_detail_ids"),
                "journal_date" => $journal_date->format("Y-m-d"),
            ],
        ]);

        if (! $partner_payout_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_payout_master_response);
        }

        S::flashSuccess($partner_payout_master_response->status_message, true);
        return redirect(route("cms.yukk_co.partner_payout_master.show", $partner_payout_master_response->result->id));
    }
}