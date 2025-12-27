<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 16-Nov-21
 * Time: 13:52
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentChannelCategoryController extends BaseController {

    public function index(Request $request) {
        $access_control = "PAYMENT_CAT_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $query_params = [
                "page" => $page,
                "per_page" => 10000,
            ];

            $payment_channel_category_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($payment_channel_category_response->is_ok) {
                $result = $payment_channel_category_response->result;

                $payment_channel_category_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.payment_channel_categories.list", [
                    "payment_channel_category_list" => $payment_channel_category_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_category_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $payment_channel_category_id) {
        $access_control = "PAYMENT_CAT_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_category_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_category_id" => $payment_channel_category_id,
                ],
            ]);

            if ($payment_channel_category_response->is_ok) {
                $result = $payment_channel_category_response->result;

                return view("yukk_co.payment_channel_categories.show", [
                    "payment_channel_category" => $result,
                ]);
            } else if ($payment_channel_category_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_category_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $payment_channel_category_id) {
        $access_control = "PAYMENT_CAT_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_category_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_category_id" => $payment_channel_category_id,
                ],
            ]);

            if ($payment_channel_category_response->is_ok) {
                $result = $payment_channel_category_response->result;

                return view("yukk_co.payment_channel_categories.edit", [
                    "payment_channel_category" => $result,
                ]);
            } else if ($payment_channel_category_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_category_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $payment_channel_category_id) {
        $access_control = "PAYMENT_CAT_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                //"description" => "required",
            ]);
            $validator->validate();

            $form_params = $request->only("name", "description");
            $form_params['id'] = $form_params['payment_channel_category_id'] = $payment_channel_category_id;

            $payment_channel_category_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_CATEGORY_EDIT_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($payment_channel_category_response->is_ok) {
                H::flashSuccess($payment_channel_category_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel_category.edit", $payment_channel_category_id));
            } else {
                H::flashFailed($payment_channel_category_response->status_message, true);
                return redirect(route("cms.yukk_co.payment_channel_category.edit", $payment_channel_category_id))->withInput();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}