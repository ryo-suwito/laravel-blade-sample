<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 14-Sep-21
 * Time: 10:46
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use function Pest\Laravel\json;

class MdrBeneficiaryController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("BENEFICIARY_MDR.VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "BENEFICIARY_MDR.VIEW",
                ]));
            }
        });
    }


    public function index(Request $request) {
        $access_control = "BENEFICIARY_MDR.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
        $date_range_string = $request->get("date_range", null);
        $beneficiary = $request->get("beneficiary", null);
        $beneficiary = is_array($beneficiary) ? implode(",", $beneficiary) : $beneficiary;

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

        $customer_list = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_LIST_COSTUMER, [
            "query" => [
                "limit" => 10,
                "page" => 1,
                "keyword" => null,
                "selected" => $beneficiary
            ]
        ]);
        $result = $customer_list->result;

        if ($customer_list->is_ok) {
            return view("yukk_co.mdr_beneficiary.index", [
                "start_time" => $start_date,
                "end_time" => $end_date,
                "result" => $result,
                "details_qris"=>null,
                "details_pg"=>null,
                "details_pg_bca"=>null,
                "detail" => null,
                "customer_id" => null,
                "partner_result" => null,
                "partner_id" => null,
                "selection_type" => 'beneficiary'
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_list);
        }
    }


    // get END_POINT_BENEFICIARY_LIST_COSTUMER by limit and page
    public function paginatedCustomer(Request $request){
        $access_control = "BENEFICIARY_MDR.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
        $keyword = $request->get("keyword", null);
        $limit = $request->get("limit", 10);
        $page = $request->get("page", 1);
        $selected = $request->get("selected", null);
        $customer_list = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_LIST_COSTUMER, [
            "query" => [
                "limit" => $limit,
                "page" => $page,
                "keyword" => $keyword,
                "selected" => $selected
            ]
        ]);
        $result = $customer_list->result;
        if ($customer_list->is_ok) {
            return Response::json([
                "status" => "success",
                "message" => "Success to get customers",
                "data" => $result
            ]);
        } else {
            return Response::json([
                "status" => "error",
                "message" => "Failed to get customers",
                "data" => null
            ]);
        }
    }
    
    public function detail(Request $request) {
        $access_control = "BENEFICIARY_MDR.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
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
        $beneficiary = $request->get("beneficiary", ['all']);
    
        $partner = $request->get("partner", ['all']);
        $query_params = [
            "customer_id" => $beneficiary,
            "partner_id" => $partner
        ];

        $selected = $request->get("beneficiary", null);
        $selected = is_array($selected) ? implode(",", $selected) : $selected;

        $customer_list = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_LIST_COSTUMER, [
            "query" => [
                "limit" => 10,
                "page" => 1,
                "keyword" => null,
                "selected" => $selected == "all" ? null : $selected
            ]
        ]);

        $selected = $request->get("partner", null);
        $selected = is_array($selected) ? implode(",", $selected) : $selected;
        $partner_list = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_LIST_PARTNER, [
            "query"=> [
                "limit" => 10,
                "page" => 1,
                "keyword" => null,
                "selected" => $selected == "all" ? null : $selected
            ]
        ]);
        $result = $customer_list->result;
        $partner_result = $partner_list->is_ok ? $partner_list->result : null;
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d H:i:s");
            $query_params["end_date"] = $end_date->format("Y-m-d H:i:s");
        }
        $summary = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_TRANSACTION_DETAIL, [
            "query"=> $query_params
        ]);
        if ($summary->is_ok) {
            $detail = $summary->result;
            return view("yukk_co.mdr_beneficiary.index", [
                "start_time" => $start_date,
                "end_time" => $end_date,
                "result" => $result,
                "details_qris"=>$detail->details_qris,
                "details_pg"=>$detail->details_pg,
                "details_pg_bca"=>$detail->details_pg_bca,
                "detail" => (object)$detail,
                "customer_id" => $beneficiary,
                "partner_result" => $partner_result ? $partner_result : null,
                "partner_id" => $partner,
                "selection_type" => $request->get("selectionType", 'beneficiary')
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($summary);
        }
    }
    // function get partners, take get query params "selected" then explode by comma return array as json
    public function getPartners(Request $request) {
        $access_control = "BENEFICIARY_MDR.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
         $keyword = $request->get("keyword", null);
        $limit = $request->get("limit", 10);
        $page = $request->get("page", 1);
        $selected = $request->get("selected", null);
        $partner_list = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BENEFICIARY_LIST_PARTNER, [
            "query" => [
                "limit" => $limit,
                "page" => $page,
                "keyword" => $keyword,
                "selected" => $selected
            ]
        ]);
        $result = $partner_list->result;
        if ($partner_list->is_ok) {
            return Response::json([
                "status" => "success",
                "message" => "Success to get partners",
                "data" => $result
            ]);
        } else {
            return Response::json([
                "status" => "error",
                "message" => "Failed to get partners",
                "data" => null
            ]);
        }
    }
}