<?php

namespace App\Http\Controllers\Customers;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisbursementController extends BaseController
{
    public function index(Request $request)
    {
        $access_control = "SETTLEMENT_CUSTOMER.VIEW_SUMMARY";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $date_range_string = $request->get("date_range", null);
        $type = $request->get("type", null);

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->subDays(6)->startOfDay();
            }
            try {
                $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_time = Carbon::now()->endOfDay();
            }
        } else {
            $start_time = Carbon::now()->subDays(6)->startOfDay();
            $end_time = Carbon::now()->endOfDay();
        }

        $query_params = [];

        if ($start_time && $end_time) {
            $query_params["start_date"] = $start_time->format("Y-m-d");
            $query_params["end_date"] = $end_time->format("Y-m-d");
        }

        if ($type) {
            $query_params["type"] = $type;
        }

        $old_type = $type;

        $disbursement_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_SUMMARY_LIST_CUSTOMER, [
            "query" => $query_params,
        ]);

        $dibursement_request = $disbursement_response->result;
        $index_qris = 1;
        $index_pg = 1;
        if ($disbursement_response->is_ok)
        {
            return view("customers.disbursement.list", [
                "disbursement_response" => $dibursement_request,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "index_qris" => $index_qris,
                "index_pg" => $index_pg,
                "old_type" => $old_type,
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($disbursement_response);
        }
    }
}
