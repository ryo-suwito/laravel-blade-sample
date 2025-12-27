<?php

namespace App\Http\Controllers\Customers;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisbursementCustomerController extends BaseController {


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

        $query_params = [
            "page" => $page,
            "per_page" => 10,
        ];
        if ($start_time && $end_time) {
            $query_params["start_date"] = $start_time->format("Y-m-d");
            $query_params["end_date"] = $end_time->format("Y-m-d");
        }
        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_CUSTOMER, [
            "query" => $query_params,
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            $disbursement_customer_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("customers.disbursement_customer.list", [
                "disbursement_customer_list" => $disbursement_customer_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_time" => $start_time,
                "end_time" => $end_time,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

    public function show(Request $request, $disbursement_customer_id) {
        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_CUSTOMER, [
            "form_params" => [
                "disbursement_customer_master_id" => $disbursement_customer_id,
            ],
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            //dd($result);
            return view("customers.disbursement_customer.show", [
                "disbursement_customer" => $result,
            ]);
        } else if ($disbursement_customer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

}
