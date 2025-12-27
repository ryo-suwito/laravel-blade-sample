<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 02-Dec-21
 * Time: 10:21
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends BaseController {

    public function index(Request $request) {
        $access_control = "PROVIDER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $query_params = [
                "page" => $page,
                "per_page" => 10000,
            ];

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($provider_response->is_ok) {
                $result = $provider_response->result;

                $provider_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;

                return view("yukk_co.providers.list", [
                    "provider_list" => $provider_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $provider_id) {
        $access_control = "PROVIDER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                ],
            ]);

            if ($provider_response->is_ok) {
                $provider = $provider_response->result;

                return view("yukk_co.providers.show", [
                    "provider" => $provider,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $provider_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                ],
            ]);

            if ($provider_response->is_ok) {
                $provider = $provider_response->result;

                return view("yukk_co.providers.edit", [
                    "provider" => $provider,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $provider_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $validator = Validator::make($request->all(), [
                "name" => "required",
            ]);
            $validator->validate();

            $payment_channel_actives = [];
            foreach ($request->get("provider_has_payment_channel") as $_provider_id => $payment_channel_ids) {
                foreach ($payment_channel_ids as $_payment_channel_id => $active) {
                    $payment_channel_actives[] = [
                        "provider_id" => $_provider_id,
                        "payment_channel_id" => $_payment_channel_id,
                        "active" => $active,
                    ];
                }
            }


            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_EDIT_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                    "name" => $request->get("name"),
                    "payment_channel_actives" => $payment_channel_actives
                ],
            ]);

            if ($provider_response->is_ok) {
                H::flashSuccess($provider_response->status_message, true);
                return redirect(route("cms.yukk_co.provider.edit", $provider_id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}