<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 01-Dec-21
 * Time: 13:22
 */

namespace App\Http\Controllers\YukkCo;

use App\Constants\PaymentGateway\ChannelCodeConstant;
use App\Constants\PaymentGateway\ProviderCodeConstant;
use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerMdrInternalController extends BaseController {

    public function index(Request $request) {
        $access_control = "PARTNER_MDR_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $query_params = [
                "page" => 1,
                "per_page" => $request->get("per_page", 10),
            ];

            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($partner_mdr_response->is_ok) {
                $result = $partner_mdr_response->result;

                $partner_mdr_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.partner_mdr_pg.list", [
                    "partner_mdr_list" => $partner_mdr_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $partner_id, $provider_id, $payment_channel_id) {
        $access_control = "PARTNER_MDR_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_ITEM_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($partner_mdr_response->is_ok) {
                $result = $partner_mdr_response->result;

                return view("yukk_co.partner_mdr_pg.show", [
                    "partner_mdr" => $result,
                ]);
            } else if ($partner_mdr_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $partner_id, $provider_id, $payment_channel_id) {
        $access_control = "PARTNER_MDR_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                ],
            ]);

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($provider_response->result->code == 'YUKK' && $payment_channel_response->result->code == 'QRIS') {
                return redirect(route("cms.yukk_co.partner_mdr_pg.item", [$partner_id, $provider_id, $payment_channel_id]));
            }

            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_ITEM_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($partner_mdr_response->is_ok) {
                $result = $partner_mdr_response->result;

                return view("yukk_co.partner_mdr_pg.edit", [
                    "partner_mdr" => $result,
                    "partner_id" => $partner_id,
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                ]);
            } else if ($partner_mdr_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $partner_id, $provider_id, $payment_channel_id) {
        $access_control = "PARTNER_MDR_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "mdr_fee_fixed" => "required|numeric",
                "mdr_fee_percentage" => "required|numeric",
                "active" => "required|in:1,0",
            ]);
            $validator->validate();

            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_EDIT_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                    "mdr_fee_fixed" => $request->get("mdr_fee_fixed"),
                    "mdr_fee_percentage" => $request->get("mdr_fee_percentage"),
                    "active" => $request->get("active"),
                ],
            ]);

            if ($partner_mdr_response->is_ok) {
                H::flashSuccess(__($partner_mdr_response->status_message), true);
                return redirect(route("cms.yukk_co.partner_mdr_pg.edit", [$partner_id, $provider_id, $payment_channel_id]));
            } else if ($partner_mdr_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request) {
        $access_control = "PARTNER_MDR_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_GET_DATA_FOR_INSERT_YUKK_CO, [
                "form_params" => [],
            ]);

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO, [
                "form_params" => [
                    "code" => ProviderCodeConstant::YUKK,
                ],
            ]);

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "code" => ChannelCodeConstant::QRIS,
                ],
            ]);

            if ($partner_mdr_response->is_ok) {
                $result = $partner_mdr_response->result;

                return view("yukk_co.partner_mdr_pg.create", [
                    "partner_list" => @$result->partner_list,
                    "provider_list" => @$result->provider_list,
                    "provider_item" => @$provider_response->result,
                    "payment_channel_item" => @$payment_channel_response->result,
                    "payment_channel_list" => @$result->payment_channel_list,
                ]);
            } else if ($partner_mdr_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request) {
        $access_control = "PARTNER_MDR_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "partner_id" => "required|integer",
                "provider_id" => "required|integer",
                "payment_channel_id" => "required|integer",
                "mdr_fee_fixed" => "required|numeric",
                "mdr_fee_percentage" => "required|numeric",
                "active" => "required|in:1,0",
            ]);
            $validator->validate();

            $partner_id = $request->get("partner_id");
            $provider_id = $request->get("provider_id");
            $payment_channel_id = $request->get("payment_channel_id");

            $partner_mdr_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_MDR_CREATE_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                    "mdr_fee_fixed" => $request->get("mdr_fee_fixed"),
                    "mdr_fee_percentage" => $request->get("mdr_fee_percentage"),
                    "active" => $request->get("active"),
                ],
            ]);

            if ($partner_mdr_response->is_ok) {
                H::flashSuccess(__($partner_mdr_response->status_message), true);
                return redirect(route("cms.yukk_co.partner_mdr_pg.edit", [$partner_id, $provider_id, $payment_channel_id]));
            } else if ($partner_mdr_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_mdr_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}
