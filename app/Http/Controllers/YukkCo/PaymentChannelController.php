<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 16-Nov-21
 * Time: 12:54
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentChannelController extends BaseController {


    public function index(Request $request) {
        $access_control = "PAYMENT_CHANNEL_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $query_params = [
                "page" => $page,
                "per_page" => 10000,
            ];

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($payment_channel_response->is_ok) {
                $result = $payment_channel_response->result;

                $payment_channel_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.payment_channels.list", [
                    "payment_channel_list" => $payment_channel_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $payment_channel_id) {
        $access_control = "PAYMENT_CHANNEL_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($payment_channel_response->is_ok) {
                $result = $payment_channel_response->result;

                return view("yukk_co.payment_channels.show", [
                    "payment_channel" => $result,
                ]);
            } else if ($payment_channel_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $payment_channel_id) {
        $access_control = "PAYMENT_CHANNEL_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            $payment_channel_category_respones = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_LIST_YUKK_CO, [
                "query" => [
                    "page" => 1,
                    "per_page" => 10000,
                ],
            ]);

            if ($payment_channel_response->is_ok) {
                if ($payment_channel_category_respones->is_ok) {
                    $payment_channel = $payment_channel_response->result;
                    $payment_channel_categories = $payment_channel_category_respones->result->data;

                    return view("yukk_co.payment_channels.edit", [
                        "payment_channel" => $payment_channel,
                        "payment_channel_categories" => $payment_channel_categories,
                    ]);
                } else {
                    return $this->getApiResponseNotOkDefaultResponse($payment_channel_category_respones);
                }
            } else if ($payment_channel_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $payment_channel_id) {
        $access_control = "PAYMENT_CHANNEL_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $post_data = $request->only(["category_id", "name", "code"]);
            $post_data["id"] = $post_data["payment_channel_id"] = $payment_channel_id;

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_EDIT_YUKK_CO, [
                "form_params" => $post_data,
            ]);

            if ($payment_channel_response->is_ok) {
                H::flashSuccess($payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel.edit", $payment_channel_id));
            } else {
                H::flashFailed($payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel.edit", $payment_channel_id))->withInput();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function editStatus(Request $request, $payment_channel_id) {
        $access_control = "PAYMENT_CHANNEL_ACTIVE_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($payment_channel_response->is_ok) {
                $payment_channel = $payment_channel_response->result;

                return view("yukk_co.payment_channels.edit_status", [
                    "payment_channel" => $payment_channel,
                ]);
            } else if ($payment_channel_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }


    public function updateStatus(Request $request, $payment_channel_id) {
        $access_control = "PAYMENT_CHANNEL_ACTIVE_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $post_data = $request->only(["active"]);
            $post_data["id"] = $post_data["payment_channel_id"] = $payment_channel_id;

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_EDIT_STATUS_YUKK_CO, [
                "form_params" => $post_data,
            ]);

            if ($payment_channel_response->is_ok) {
                H::flashSuccess($payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel.edit_status", $payment_channel_id));
            } else {
                H::flashFailed($payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel.edit_status", $payment_channel_id))->withInput();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}