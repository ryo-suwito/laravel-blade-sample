<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 24-Jun-22
 * Time: 14:51
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeneficiaryEditRequestController extends BaseController {

    public function listCooPending(Request $request) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.VIEW_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $order_id = $request->get("order_id", null);
        $date_range_string = $request->get("date_range", null);
        $customer_name = $request->get("customer_name", null);
        $bank_type = $request->get("bank_type", "ALL");

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->startOfDay()->subDays(6);
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->startOfDay()->subDays(6);
            $end_date = Carbon::now()->endOfDay();
        }

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_csv") ? 9999999 : 100,
            "status_list" => ["PENDING"],
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d H:i:s");
            $query_params["end_date"] = $end_date->format("Y-m-d H:i:s");
        }
        if ($customer_name) {
            $query_params["customer_name"] = $customer_name;
        }
        if ($bank_type != "ALL") {
            $query_params["bank_type"] = $bank_type;
        }
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);
        //dd($beneficiary_edit_response);

        if ($beneficiary_edit_response->is_ok) {
            $result = $beneficiary_edit_response->result;

            $beneficiary_edit_request_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("yukk_co.beneficiary_edit_requests.list_coo", [
                "beneficiary_edit_request_list" => $beneficiary_edit_request_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "order_id" => $order_id,
                "start_time" => $start_date,
                "end_time" => $end_date,
                "customer_name" => $customer_name,
                "bank_type" => $bank_type,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function listCooApproved(Request $request) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.VIEW_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $order_id = $request->get("order_id", null);
        $date_range_string = $request->get("date_range", null);
        $customer_name = $request->get("customer_name", null);
        $bank_type = $request->get("bank_type", "ALL");

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->startOfDay()->subDays(6);
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->startOfDay()->subDays(6);
            $end_date = Carbon::now()->endOfDay();
        }

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_csv") ? 9999999 : 100,
            "status_list" => ["APPROVED_COO", "APPROVED_CFO", "SUCCESS"],
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d H:i:s");
            $query_params["end_date"] = $end_date->format("Y-m-d H:i:s");
        }
        if ($customer_name) {
            $query_params["customer_name"] = $customer_name;
        }
        if ($bank_type != "ALL") {
            $query_params["bank_type"] = $bank_type;
        }
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);
        //dd($beneficiary_edit_response);

        if ($beneficiary_edit_response->is_ok) {
            $result = $beneficiary_edit_response->result;

            $beneficiary_edit_request_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("yukk_co.beneficiary_edit_requests.list_coo_approved", [
                "beneficiary_edit_request_list" => $beneficiary_edit_request_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "order_id" => $order_id,
                "start_time" => $start_date,
                "end_time" => $end_date,
                "customer_name" => $customer_name,
                "bank_type" => $bank_type,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function listCooRejected(Request $request) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.VIEW_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $order_id = $request->get("order_id", null);
        $date_range_string = $request->get("date_range", null);
        $customer_name = $request->get("customer_name", null);
        $bank_type = $request->get("bank_type", "ALL");

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->startOfDay()->subDays(6);
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->startOfDay()->subDays(6);
            $end_date = Carbon::now()->endOfDay();
        }

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_csv") ? 9999999 : 100,
            "status_list" => ["REJECTED_COO", "REJECTED_CFO"],
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d H:i:s");
            $query_params["end_date"] = $end_date->format("Y-m-d H:i:s");
        }
        if ($customer_name) {
            $query_params["customer_name"] = $customer_name;
        }
        if ($bank_type != "ALL") {
            $query_params["bank_type"] = $bank_type;
        }
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);
        //dd($beneficiary_edit_response);

        if ($beneficiary_edit_response->is_ok) {
            $result = $beneficiary_edit_response->result;

            $beneficiary_edit_request_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;

            return view("yukk_co.beneficiary_edit_requests.list_coo_rejected", [
                "beneficiary_edit_request_list" => $beneficiary_edit_request_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "order_id" => $order_id,
                "start_time" => $start_date,
                "end_time" => $end_date,
                "customer_name" => $customer_name,
                "bank_type" => $bank_type,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function showCoo(Request $request, $beneficiary_edit_request_id) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.VIEW_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_ITEM_YUKK_CO, [
            "form_params" => [
                "beneficiary_edit_request_id" => $beneficiary_edit_request_id,
            ],
        ]);
        //dd($beneficiary_edit_response);

        if ($beneficiary_edit_response->is_ok) {
            $beneficiary_edit_request = $beneficiary_edit_response->result;

            if ($beneficiary_edit_request->status != "PENDING") {
                return abort(404);
            }
            return view("yukk_co.beneficiary_edit_requests.show_coo", [
                "beneficiary_edit_request" => $beneficiary_edit_request,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function editCoo(Request $request, $beneficiary_edit_request_id) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.EDIT_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_ITEM_YUKK_CO, [
            "form_params" => [
                "beneficiary_edit_request_id" => $beneficiary_edit_request_id,
            ],
        ]);
        //dd($beneficiary_edit_response);

        if ($beneficiary_edit_response->is_ok) {
            $beneficiary_edit_request = $beneficiary_edit_response->result;

            return view("yukk_co.beneficiary_edit_requests.edit_coo", [
                "beneficiary_edit_request" => $beneficiary_edit_request,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function updateCoo(Request $request, $beneficiary_edit_request_id) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.EDIT_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "disbursement_fee" => "required|numeric",
            "disbursement_interval" => "required|in:DAILY,WEEKLY",
        ]);
        $validator->validate();

        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_EDIT_COO_YUKK_CO, [
            "form_params" => [
                "beneficiary_edit_request_id" => $beneficiary_edit_request_id,
                "disbursement_fee" => $request->get("disbursement_fee"),
                "auto_disbursement_interval" => $request->get("disbursement_interval"),
            ],
        ]);

        if ($beneficiary_edit_response->is_ok) {
            S::flashSuccess($beneficiary_edit_response->status_message, true);
            return redirect(route("cms.yukk_co.beneficiary_edit_request.show", $beneficiary_edit_request_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function approveCoo(Request $request, $beneficiary_edit_request_id) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.APPROVE_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_APPROVE_COO_YUKK_CO, [
            "form_params" => [
                "beneficiary_edit_request_id" => $beneficiary_edit_request_id,
            ],
        ]);

        if ($beneficiary_edit_response->is_ok) {
            S::flashSuccess($beneficiary_edit_response->status_message, true);
            //return redirect(route("cms.yukk_co.beneficiary_edit_request.show", $beneficiary_edit_request_id));
            return redirect(route("cms.yukk_co.beneficiary_edit_request.list_coo_approved"));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

    public function rejectCoo(Request $request, $beneficiary_edit_request_id) {
        $access_control = "BENEFICIARY_EDIT_REQUEST.REJECT_COO";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "reject_remark" => "required",
        ]);
        $validator->validate();

        $beneficiary_edit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_BENEFICIARY_BENEFICIARY_EDIT_REQUEST_REJECT_COO_YUKK_CO, [
            "form_params" => [
                "beneficiary_edit_request_id" => $beneficiary_edit_request_id,
                "reject_remark" => $request->get("reject_remark"),
            ],
        ]);

        if ($beneficiary_edit_response->is_ok) {
            S::flashSuccess($beneficiary_edit_response->status_message, true);
            //return redirect(route("cms.yukk_co.beneficiary_edit_request.show", $beneficiary_edit_request_id));
            return redirect(route("cms.yukk_co.beneficiary_edit_request.list_coo_rejected"));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($beneficiary_edit_response);
        }
    }

}