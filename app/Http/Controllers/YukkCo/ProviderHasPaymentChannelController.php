<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 02-Dec-21
 * Time: 10:58
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderHasPaymentChannelController extends BaseController {

    public function show(Request $request, $provider_id, $payment_channel_id) {
        $access_control = "PROVIDER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $provider_has_payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($provider_has_payment_channel_response->is_ok) {
                $provider_has_payment_channel = $provider_has_payment_channel_response->result;

                return view("yukk_co.provider_has_payment_channels.show", [
                    "provider_has_payment_channel" => $provider_has_payment_channel,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_has_payment_channel_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request, $provider_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $payment_channel_list_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "query" => [
                    "page" => 1,
                    "per_page" => 9999999,
                ],
            ]);
            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                ],
            ]);


            if ($payment_channel_list_response->is_ok && $provider_response->is_ok) {
                $payment_channel_list_all = $payment_channel_list_response->result->data;
                $provider = $provider_response->result;

                for ($i = 0; $i < count($payment_channel_list_all); $i++) {
                    $payment_channel_list_all[$i]->available = ! in_array($payment_channel_list_all[$i]->id, collect($provider->provider_has_payment_channels)->pluck("payment_channel_id")->toArray());
                }

                return view("yukk_co.provider_has_payment_channels.create", [
                    "provider" => $provider,
                    "payment_channel_list" => $payment_channel_list_all,
                ]);
            } else if (! $payment_channel_list_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_list_response);
            } else /*if (! $payment_channel_list_response->is_ok)*/ {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request, $provider_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "payment_channel_id" => "required|integer",
                //"provider_channel_code" => "required",
                "active" => "required|in:0,1",
                "provider_fee_percentage" => "required|numeric|min:0|max:100",
                "provider_fee_fixed" => "required|numeric|min:0",

                //"bank_account_name" => "required",
                //"bank_account_number" => "required",
                "source_of_fund_account_name" => "required",
                "source_of_fund_account_number" => "required",

                "cut_of_date" => "required|integer|min:1|max:100",
                "coa_number_rek_settlement" => "required",
                "coa_number_pendapatan" => "required",
            ]);
            $validator->validate();

            $provider_has_payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_CREATE_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                    "provider_channel_code" => $request->get("provider_channel_code", ""),

                    "payment_channel_id" => $request->get("payment_channel_id"),
                    "active" => $request->get("active"),
                    "provider_fee_percentage" => $request->get("provider_fee_percentage"),
                    "provider_fee_fixed" => $request->get("provider_fee_fixed"),

                    "bank_account_name" => null,
                    "bank_account_number" => null,
                    "source_of_fund_account_name" => $request->get("source_of_fund_account_name"),
                    "source_of_fund_account_number" => $request->get("source_of_fund_account_number"),

                    "settlement_cut_off_start_date" => $request->get("cut_of_date"),
                    "settlement_cut_off_end_date" => $request->get("cut_of_date"),
                    "settlement_cut_off_start_time" => "00:00:00",
                    "settlement_cut_off_end_time" => "23:59:59",

                    "coa_number_rek_settlement" => $request->get("coa_number_rek_settlement"),
                    "coa_number_pendapatan" => $request->get("coa_number_pendapatan"),
                ],
            ]);

            if ($provider_has_payment_channel_response->is_ok) {
                $provider_has_payment_channel = $provider_has_payment_channel_response->result;

                H::flashSuccess($provider_has_payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.provider_has_payment_channel.item", [$provider_has_payment_channel->provider_id, $provider_has_payment_channel->payment_channel_id]));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_has_payment_channel_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }


    public function edit(Request $request, $provider_id, $payment_channel_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $provider_has_payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_ITEM_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                ],
            ]);

            if ($provider_has_payment_channel_response->is_ok) {
                $provider_has_payment_channel = $provider_has_payment_channel_response->result;

                return view("yukk_co.provider_has_payment_channels.edit", [
                    "provider_has_payment_channel" => $provider_has_payment_channel,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_has_payment_channel_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $provider_id, $payment_channel_id) {
        $access_control = "PROVIDER_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                //"provider_channel_code" => "required",
                "active" => "required|in:0,1",
                "provider_fee_percentage" => "required|numeric|min:0|max:100",
                "provider_fee_fixed" => "required|numeric|min:0",

                //"bank_account_name" => "required",
                //"bank_account_number" => "required",
                "source_of_fund_account_name" => "required",
                "source_of_fund_account_number" => "required",

                "cut_of_date" => "required|integer|min:1|max:100",
            ]);
            $validator->validate();

            $provider_has_payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_HAS_PAYMENT_CHANNEL_EDIT_YUKK_CO, [
                "form_params" => [
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                    "provider_channel_code" => $request->get("provider_channel_code", ""),

                    "active" => $request->get("active"),
                    "provider_fee_percentage" => $request->get("provider_fee_percentage"),
                    "provider_fee_fixed" => $request->get("provider_fee_fixed"),

                    "bank_account_name" => null,
                    "bank_account_number" => null,
                    "source_of_fund_account_name" => $request->get("source_of_fund_account_name"),
                    "source_of_fund_account_number" => $request->get("source_of_fund_account_number"),

                    "settlement_cut_off_start_date" => $request->get("cut_of_date"),
                    "settlement_cut_off_end_date" => $request->get("cut_of_date"),
                    "settlement_cut_off_start_time" => "00:00:00",
                    "settlement_cut_off_end_time" => "23:59:59",
                ],
            ]);

            if ($provider_has_payment_channel_response->is_ok) {
                $provider_has_payment_channel = $provider_has_payment_channel_response->result;

                H::flashSuccess($provider_has_payment_channel_response->status_message, true);
                return redirect(route("cms.yukk_co.provider_has_payment_channel.item", [$provider_has_payment_channel->provider_id, $provider_has_payment_channel->payment_channel_id]));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($provider_has_payment_channel_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}