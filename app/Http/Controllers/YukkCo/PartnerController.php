<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 06-Dec-21
 * Time: 14:45
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;

class PartnerController extends BaseController {

    public function index(Request $request) {
        $access_control = "PARTNER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $query_params = [
                "page" => $page,
                "per_page" => $request->get("per_page", 10),
            ];


            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($partner_response->is_ok) {
                $result = $partner_response->result;

                $partner_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;
                return view("yukk_co.partners.list", [
                    "partner_list" => $partner_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $partner_id) {
        $access_control = "PARTNER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_ITEM_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                ],
            ]);

            if ($partner_response->is_ok) {
                $result = $partner_response->result;

                return view("yukk_co.partners.show", [
                    "partner" => $result,
                ]);
            } else if ($partner_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $partner_id) {
        $access_control = "PARTNER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_ITEM_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                ],
            ]);
            if ($partner_response->is_ok) {
                $result = $partner_response->result;

                return view("yukk_co.partners.edit", [
                    "partner" => $result,
                ]);
            } else if ($partner_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $partner_id) {
        $access_control = "PARTNER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_ids = [];
            if ($request->has("payment_channel")) {
                $payment_channel_input = $request->get("payment_channel");
                foreach ($payment_channel_input as $payment_channel_id => $on) {
                    $payment_channel_ids[] = $payment_channel_id;
                }
            }

            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_EDIT_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                    "payment_channel_ids" => $payment_channel_ids,
                    "permission" => $request->has('permission'),
                    "webhook_partner" => $request->webhook_partner,
                ],
            ]);

            if ($partner_response->is_ok) {
                H::flashSuccess($partner_response->status_message, true);
                return redirect(route("cms.yukk_co.partner.edit", $partner_id));
            } else if ($partner_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function generateClientIdSecret(Request $request, $partner_id) {
        $access_control = "PARTNER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_GENERATE_CLIENT_ID_SECRET_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                ],
            ]);

            if ($partner_response->is_ok) {
                H::flashSuccess($partner_response->status_message, true);
                return redirect(route("cms.yukk_co.partner.edit", $partner_id));
            } else if ($partner_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
