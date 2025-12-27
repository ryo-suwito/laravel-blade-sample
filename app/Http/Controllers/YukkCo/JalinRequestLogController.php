<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 19-Nov-21
 * Time: 13:45
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JalinRequestLogController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("JALIN_LOG_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "JALIN_LOG_VIEW",
                ]));
            }
        });
    }

    public function indexInbound(Request $request) {
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

        if ($start_time->diffInDays($end_time) > 7) {
            $end_time = $start_time->copy()->addDays(7);
        }

        $query_params = [
            "page" => $page,
            "per_page" => 100,
            "start_time" => $start_time->format("Y-m-d H:i:s"),
            "end_time" => $end_time->format("Y-m-d H:i:s"),
            "order_by" => "created_at",
        ];

        if ($request->has("keyword")) {
            $keyword = $request->get("keyword", null);
            if ($keyword) {
                $query_params['keyword'] = $keyword;
            }
        }

        if ($request->has("rrn")) {
            $rrn = $request->get("rrn", null);
            if ($rrn) {
                $query_params['rrn'] = $rrn;
            }
        }

        if ($request->has("transaction_amount")) {
            $transaction_amount = $request->get("transaction_amount", null);
            if ($transaction_amount) {
                $query_params['transaction_amount'] = $transaction_amount;
            }
        }

        $error_merchant_branch = null;
        if ($request->has("merchant_name")) {
            $merchant_name = $request->get("merchant_name", null);
            if ($merchant_name) {
                $query_params['merchant_name'] = $merchant_name;

                if (isset($query_params['rrn']) || isset($query_params['transaction_amount'])) {

                } else {
                    unset($query_params['merchant_name']);
                    $error_merchant_branch = trans("cms.jalin_inbound_search_error_only_merchant_name");
                }
            }
        }

        $jalin_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_LOG_LIST_INBOUND_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($jalin_log_response->is_ok) {
            $result = $jalin_log_response->result;
            $jalin_request_log_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            return view("yukk_co.jalin_logs.list_inbound", [
                "jalin_request_log_list" => $jalin_request_log_list,
                "keyword" => $request->get("keyword", ""),
                "rrn" => $request->get("rrn", ""),
                "start_time" => $start_time,
                "end_time" => $end_time,
                "showing_data" => [
                    "from" => $result->from,
                    "to" => $result->to,
                    "total" => $result->total,
                ],
                "transaction_amount" => $request->get("transaction_amount", ""),
                "merchant_name" => $request->get("merchant_name", ""),
                "current_page" => $current_page,
                "last_page" => $last_page,
                "error_merchant_branch" => $error_merchant_branch,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($jalin_log_response);
        }
    }

    public function showInbound(Request $request, $log_id) {
        $jalin_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_LOG_ITEM_INBOUND_YUKK_CO, [
            "form_params" => ["id" => $log_id],
        ]);

        if ($jalin_log_response->is_ok) {
            $result = $jalin_log_response->result;

            return view("yukk_co.jalin_logs.show_inbound", [
                "jalin_request_log" => $result,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($jalin_log_response);
        }
    }

    public function indexOutbound(Request $request) {
        $page = $request->get("page", 1);

        $query_params = [
            "page" => $page,
            "per_page" => $request->get("per_page", 100),
        ];

        if ($request->has("keyword")) {
            $keyword = $request->get("keyword", null);
            $query_params['keyword'] = $keyword;
        }

        $jalin_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_LOG_LIST_OUTBOUND_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($jalin_log_response->is_ok) {
            $result = $jalin_log_response->result;
            $jalin_request_log_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            return view("yukk_co.jalin_logs.list_outbound", [
                "jalin_request_log_list" => $jalin_request_log_list,
                "keyword" => $request->get("keyword", ""),
                "current_page" => $current_page,
                "last_page" => $last_page,
                "showing_data" => [
                    "from" => $result->from,
                    "to" => $result->to,
                    "total" => $result->total,
                ],
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($jalin_log_response);
        }
    }

    public function showOutbound(Request $request, $log_id) {
        $jalin_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_LOG_ITEM_OUTBOUND_YUKK_CO, [
            "form_params" => ["id" => $log_id],
        ]);

        if ($jalin_log_response->is_ok) {
            $result = $jalin_log_response->result;

            return view("yukk_co.jalin_logs.show_outbound", [
                "jalin_request_log" => $result,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($jalin_log_response);
        }
    }
}
