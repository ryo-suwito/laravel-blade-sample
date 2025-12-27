<?php
/**
 * Created by PhpStorm.
 * User: loren
 * Date: 06-Jul-23
 * Time: 15:58
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\S;
use Illuminate\Http\Request;

class QrisReHitRintisController extends BaseController {

    public function index(Request $request) {
        $access_control = "QRIS_RE_HIT.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $keyword = $request->get("keyword", null);
        $status = $request->get("status", "PENDING");
        $query_params = [];
        $query_params["page"] = $page;
        if ($request->has("export_to_csv")) {
            $query_params["per_page"] = 10000000;
        } else {
            $query_params["per_page"] = 20;
        }

        if ($keyword) {
            $query_params["keyword"] = $keyword;
        }
        if ($status) {
            $query_params["status"] = $status;
        }


        $qris_re_hit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_RINTIS_QRIS_RE_HIT_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if (! $qris_re_hit_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
        }

        $result = $qris_re_hit_response->result;
        //dd($result);

        $qris_re_hit_list = $result->data;
        $current_page = $result->current_page;
        $last_page = $result->last_page;
        //dd($transaction_payment_response);
        return view("yukk_co.qris_re_hit_rintis.list", [
            "qris_re_hit_list" => $qris_re_hit_list,
            "current_page" => $current_page,
            "last_page" => $last_page,
            "keyword" => $keyword,
            "status" => $status,
        ]);
    }


    public function show(Request $request, $qris_re_hit_id) {
        $access_control = "QRIS_RE_HIT.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $qris_re_hit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_RINTIS_QRIS_RE_HIT_ITEM_YUKK_CO, [
            "form_params" => [
                "qris_re_hit_request_id" => $qris_re_hit_id,
            ],
        ]);

        if (! $qris_re_hit_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
        }

        $qris_re_hit = $qris_re_hit_response->result;

        //dd($transaction_payment_response);
        return view("yukk_co.qris_re_hit_rintis.show", [
            "qris_re_hit" => $qris_re_hit,
        ]);
    }

    public function create(Request $request) {
        $access_control = "QRIS_RE_HIT.CREATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        return view("yukk_co.qris_re_hit_rintis.create", [
        ]);
    }

    public function store(Request $request) {
        $access_control = "QRIS_RE_HIT.CREATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $qris_re_hit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_RINTIS_QRIS_RE_HIT_STORE_YUKK_CO, [
            "body" => $request->get("json"),
        ]);

        if (! $qris_re_hit_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
        }

        $qris_re_hit = $qris_re_hit_response->result;

        if ($qris_re_hit) {
            S::flashSuccess($qris_re_hit_response->status_message, true);
            return redirect(route("cms.yukk_co.qris_re_hit_rintis.item", $qris_re_hit->id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
        }
    }

    public function release(Request $request, $qris_re_hit_id) {
        $access_control = "QRIS_RE_HIT.RELEASE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        if ($request->has("approve")) {
            $qris_re_hit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_RINTIS_QRIS_RE_HIT_APPROVE_YUKK_CO, [
                "form_params" => [
                    "qris_re_hit_request_id" => $qris_re_hit_id,
                ],
            ]);

            if (! $qris_re_hit_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
            }

            $qris_re_hit = $qris_re_hit_response->result;

            S::flashSuccess($qris_re_hit_response->status_message, true);
            return redirect(route("cms.yukk_co.qris_re_hit_rintis.item", $qris_re_hit->id));
        } else if ($request->has("reject")) {
            $qris_re_hit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_RINTIS_QRIS_RE_HIT_REJECT_YUKK_CO, [
                "form_params" => [
                    "qris_re_hit_request_id" => $qris_re_hit_id,
                ],
            ]);

            if (! $qris_re_hit_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($qris_re_hit_response);
            }

            $qris_re_hit = $qris_re_hit_response->result;

            S::flashSuccess($qris_re_hit_response->status_message, true);
            return redirect(route("cms.yukk_co.qris_re_hit_rintis.item", $qris_re_hit->id));
        } else {
            S::flashFailed("QRIS Re Hit Release Action not defined", true);
            return redirect(back());
        }
    }

}