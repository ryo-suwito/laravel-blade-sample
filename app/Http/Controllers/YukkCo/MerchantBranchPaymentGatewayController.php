<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 16-Nov-21
 * Time: 16:55
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;

class MerchantBranchPaymentGatewayController extends BaseController {

    public function index(Request $request) {
        $access_control = "MERCHANT_BRANCHES_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);
            $keyword = $request->get("keyword");

            $query_params = [
                "page" => $page,
                "per_page" => $request->get("per_page", 10),
            ];

            $merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_MERCHANT_BRANCH_LIST_YUKK_CO, [
                "query" => $query_params,
                "form_params" => [
                    "search" => $keyword,
                ],
            ]);

            if ($merchant_branch_response->is_ok) {
                $result = $merchant_branch_response->result;

                $merchant_branch_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;
                return view("yukk_co.merchant_branch_payment_gateway.list", [
                    "merchant_branch_list" => $merchant_branch_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "keyword" => $keyword,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($merchant_branch_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $merchant_branch_id) {
        $access_control = "MERCHANT_BRANCHES_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_MERCHANT_BRANCH_ITEM_YUKK_CO, [
                "form_params" => [
                    "merchant_branch_id" => $merchant_branch_id,
                ],
            ]);

            if ($merchant_branch_response->is_ok) {
                $result = $merchant_branch_response->result;

                $merchant_branch = $result->merchant_branch;
                $payment_channel_list = $result->payment_channel_list;
                $merchant_branches_has_payment_channels = $result->merchant_branches_has_payment_channels ? collect($result->merchant_branches_has_payment_channels) : collect([]);

                $merchant_branches_has_payment_channel_map = $merchant_branches_has_payment_channels->mapWithKeys(function($item) {
                    return [$item->payment_channel_id => $item];
                });


                /*$payment_channel_ids = $merchant_branches_has_payment_channels ? collect($merchant_branches_has_payment_channels)->pluck("payment_channel_id") : collect([]);
                for ($i = 0; $i < count($payment_channel_list); $i++) {
                    $payment_channel_list[$i]->is_active = in_array($payment_channel_list[$i]->id, $payment_channel_ids->toArray());
                }*/

                return view("yukk_co.merchant_branch_payment_gateway.show", [
                    "merchant_branch" => $merchant_branch,
                    "payment_channel_list" => $payment_channel_list,
                    "merchant_branches_has_payment_channel_map" => $merchant_branches_has_payment_channel_map,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($merchant_branch_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $merchant_branch_id) {
        $access_control = "MERCHANT_BRANCHES_ITEM_PC_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_MERCHANT_BRANCH_ITEM_YUKK_CO, [
                "form_params" => [
                    "merchant_branch_id" => $merchant_branch_id,
                ],
            ]);

            if ($merchant_branch_response->is_ok) {
                $result = $merchant_branch_response->result;

                $merchant_branch = $result->merchant_branch;
                $payment_channel_list = $result->payment_channel_list;
                $merchant_branches_has_payment_channels = $result->merchant_branches_has_payment_channels ? collect($result->merchant_branches_has_payment_channels) : collect([]);

                $merchant_branches_has_payment_channel_map = $merchant_branches_has_payment_channels->mapWithKeys(function($item) {
                    return [$item->payment_channel_id => $item];
                });

                //dd($merchant_branches_has_payment_channel_map);

                /*$payment_channel_ids = $merchant_branches_has_payment_channels ? collect($merchant_branches_has_payment_channels)->pluck("payment_channel_id") : collect([]);
                for ($i = 0; $i < count($payment_channel_list); $i++) {
                    $payment_channel_list[$i]->is_active = in_array($payment_channel_list[$i]->id, $payment_channel_ids->toArray());
                }*/

                return view("yukk_co.merchant_branch_payment_gateway.edit", [
                    "merchant_branch" => $merchant_branch,
                    "payment_channel_list" => $payment_channel_list,
                    "merchant_branches_has_payment_channel_map" => $merchant_branches_has_payment_channel_map,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($merchant_branch_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $merchant_branch_id) {
        $access_control = "MERCHANT_BRANCHES_ITEM_PC_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $form_params = $request->except("_token");
            $form_params["id"] = $form_params["merchant_branch_id"] = $merchant_branch_id;
            $merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_MERCHANT_BRANCH_EDIT_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($merchant_branch_response->is_ok) {
                H::flashSuccess($merchant_branch_response->status_message, true);
                return redirect(route("cms.yukk_co.merchant_branch_pg.edit", $merchant_branch_id));
            } else {
                H::flashFailed($merchant_branch_response->status_message, true);
                return redirect(route("cms.yukk_co.merchant_branch_pg.edit", $merchant_branch_id))->withInput();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
