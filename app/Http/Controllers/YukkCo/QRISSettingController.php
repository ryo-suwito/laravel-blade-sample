<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QRISSettingController extends BaseController
{
    private $ccs = [
        'training@yukk.me',
        'account@yukk.me'
    ];

    public function index(Request $request)
    {
        $access_control = ["QRIS_MENU.MANAGE_QRIS.VIEW", "QRIS_MENU.MANAGE_QRIS.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $merchant = $request->get('merchant', null);
            $branch = $request->get('branch', null);
            $status = $request->get('status', null);
            $snap_category = $request->get('snap_category', null);

            $page = $request->get("page", 1);
            $per_page = $request->get("per_page", 10);
            $access_control = json_decode(S::getUserRole()->role->access_control);

            $query_params = [
                "page" => $page,
                "per_page" => $per_page,
                "merchant" => $merchant,
                "branch" => $branch,
                "status" => $status,
                "snap_category" => $snap_category
            ];

            $qris_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_LIST_YUKK_CO, [
                'query' => $query_params,
            ]);

            if ($qris_response->is_ok) {
                $result = $qris_response->result;
                $qris_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;

                return view("yukk_co.qris_settings.list", [
                    'qris_list' => $qris_list,
                    'current_page' => $current_page,
                    'last_page' => $last_page,
                    'merchant' => $merchant,
                    'branch' => $branch,
                    'status' => $status,
                    'snap_category' => $snap_category,
                    'access_control' => $access_control,

                    'per_page' => $per_page,

                    'showing_data' => [
                        'from' => $qris_response->result->from,
                        'to' => $qris_response->result->to,
                        'total' => $qris_response->result->total,
                    ],
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($qris_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function published(Request $request, $branch_id)
    {
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $qris_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_EDIT_YUKK_CO, [
                'form_params' => [
                    'id' => $branch_id,
                ],
            ]);

            $partner_fee = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_LIST_YUKK_CO, []);
            $event =  ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_LIST_YUKK_CO, []);

            if ($qris_response->is_ok) {
                $response = $qris_response->result;

                return view("yukk_co.qris_settings.published", [
                    'item' => $response->merchant_branch,
                    'partner_has_merchant_branch' => $response->partner_has_merchant_branch,
                    'partner_fees' => $partner_fee->result,
                    'events' => $event->result,
                ]);
            } else {
                H::flashFailed($qris_response->status_message, true);
                return back();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request){

        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_CREATE_YUKK_CO, [
                'form_params' => [
                    'merchant_id' => $request->get('merchant_id'),
                    'merchant_branch_id' => $request->get('merchant_branch_id'),
                    'customer_id' => $request->get('customer_id'),
                    'partner_id' => $request->get('partner_id'),
                    'partner_fee_id' => $request->get('partner_fee_id'),
                    'event_id' => $request->get('event'),

                    'currency_type' => $request->get('currency_type'),
                    'min_discount' => $request->get('min_discount'),
                    'discount' => $request->get('discount'),
                    'service_charge' => $request->get('service_charge'),
                    'tax' => $request->get('tax'),

                    'grant_type' => $request->get('grant_type'),
                    'is_snap_enabled' => $request->get('hidden_is_partner_snap'),

                    'store_id_dynamic' => $request->get('store_id_dynamic'),

                    'client_id_dynamic' => $request->get('snap_client_id_dynamic'),
                    'client_secret_dynamic' => $request->get('snap_client_secret_dynamic'),

                    'payment_notify_mode' => $request->get('payment_notify_mode') == 'WEBHOOK_PG' ? 'WEBHOOK' : $request->get('payment_notify_mode'),
                    'webhook_url' => $request->get('webhook_url'),
                    'is_payment_gateway' => $request->get('payment_notify_mode') == 'WEBHOOK_PG' ? 1 : 0,

                    'partner_order_timeout' => $request->get('partner_order_timeout'),
                    'partner_order_id_max_payment' => $request->get('partner_order_id_max_payment'),
                    'partner_order_id_prefix' => $request->get('partner_order_id_prefix'),
                    'partner_order_id_length' => $request->get('partner_order_id_length'),

                    'grant_type_static' => $request->get('grant_type_static'),
                    'client_id_static' => $request->get('snap_client_id_static'),
                    'client_secret_static' => $request->get('snap_client_secret_static'),

                    'payment_notify_mode_static' => $request->get('payment_notify_mode_static'),
                    'webhook_url_static' => $request->get('webhook_url_static'),
                ]
            ]);

            if ($response->is_ok){
                H::flashSuccess($response->status_message, true);
                return redirect(route("yukk_co.partner_login.add_from_qris", ['merchant_id' => $response->result->merchant_id, 'branch_id' => $response->result->merchant_branch_id]));
            }else if ($response->status_code == '7999'){
                H::flashFailed($response->status_message, true);
                return back();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $branch_id){
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $qris_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_DETAIL_YUKK_CO, [
                'form_params' => [
                    'id' => $branch_id,
                ],
            ]);

            $event =  ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_LIST_YUKK_CO, []);

            if ($qris_response->is_ok) {
                $merchant_response = $qris_response->result->branch;
                $edc_list = $qris_response->result->branch->edcs;

                $edc_sticker = collect($edc_list)->where("type", "STICKER");
                $edc_dynamic = collect($edc_list)->where("type", "QRIS_DYNAMIC");

                $partner_login = $qris_response->result->partner_login;

                return view("yukk_co.qris_settings.edit", [
                    'merchant_branch' => $merchant_response,
                    'edc_list' => $edc_list,
                    'edc_dynamic_list' => $edc_dynamic,
                    'edc_sticker' => $edc_sticker,
                    'events' => $event->result,
                    'partner_logins' => $partner_login,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($qris_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $branch_id){
        $access_control = "QRIS_MENU.MANAGE_QRIS.VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $qris_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_DETAIL_YUKK_CO, [
                'form_params' => [
                    'id' => $branch_id,
                ],
            ]);

            $partner_fee = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_LIST_YUKK_CO, []);
            $event =  ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_LIST_YUKK_CO, []);

            if ($qris_response->is_ok) {
                $merchant_response = $qris_response->result->branch;
                $edc_list = $qris_response->result->branch->edcs;

                $edc_sticker = collect($edc_list)->where("type", "STICKER");
                $edc_dynamic = collect($edc_list)->where("type", "QRIS_DYNAMIC");

                $partner_login = $qris_response->result->partner_login;

                return view("yukk_co.qris_settings.detail", [
                    'merchant_branch' => $merchant_response,
                    'edc_list' => $edc_list,
                    'edc_dynamic_list' => $edc_dynamic,
                    'edc_sticker' => $edc_sticker,
                    'partner_fees' => $partner_fee->result,
                    'events' => $event->result,
                    'partner_logins' => $partner_login,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($qris_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function preview(Request $request, $branch_id){
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $qris_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_DETAIL_YUKK_CO, [
                'form_params' => [
                    'id' => $branch_id,
                ],
            ]);
            $cc = implode(', ', $this->ccs);

            $edc_list = $qris_response->result->branch->edcs;
            $edc_sticker = collect($edc_list)->where("type", "STICKER");
            $edc_dynamic = collect($edc_list)->where("type", "QRIS_DYNAMIC");
            $partner_login = $qris_response->result->partner_login;

            if ($partner_login){
                return view("yukk_co.qris_settings.mail", [
                    'branch' => $qris_response->result->branch,
                    'cc' => $cc,
                    'edc_dynamic' => $edc_dynamic,
                    'edc_static' => $edc_sticker,
                    'partner_login' => $partner_login,
                ]);
            }else{
                H::flashFailed('There is no Credential to share in this branch.', true);
                return back();
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function mail(Request $request, $branch_id)
    {
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $responses = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_MANAGE_QRIS_DETAIL_YUKK_CO, [
                'form_params' => [
                    'id' => $branch_id,
                ],
            ]);

            $is_dynamic = $request->get('is_dynamic');
            $is_static = $request->get('is_static');
            $partner_logins = $responses->result->partner_login;

            $mail = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SEND_EMAIL, [
                "form_params" => [
                    'branch_id' => $branch_id,
                    'email_to' => $request->get('email_to'),
                    'email_cc' => $request->get('email_cc'),
                    'recipient_name' => $request->get('recipient_name'),
                    'partner_login' => $partner_logins,
                    'is_dynamic' => $is_dynamic,
                    'is_static' => $is_static
                ],
            ]);

            if ($mail->is_ok){
                H::flashSuccess($mail->status_message, true);
                return redirect(route("yukk_co.qris_setting.list"));
            }else{
                Log::error($mail->status_message);
                H::flashFailed($mail->status_message, true);
                return back();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function createDynamic(Request $request, $branch_id){
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_CREATE_DYNAMIC_YUKK_CO, [
                'form_params' => [
                    'merchant_id' => $request->get('merchant_id'),
                    'merchant_branch_id' => $request->get('merchant_branch_id'),
                    'customer_id' => $request->get('customer_id'),
                    'partner_id' => $request->get('partner_id'),
                    'partner_fee_id' => $request->get('partner_fee_id'),
                    'event_id' => $request->get('event'),

                    'currency_type' => $request->get('currency_type'),
                    'min_discount' => $request->get('min_discount'),
                    'discount' => $request->get('discount'),
                    'service_charge' => $request->get('service_charge'),
                    'tax' => $request->get('tax'),

                    'grant_type' => $request->get('grant_type'),
                    'is_partner_snap' => $request->get('hidden_is_partner_snap'),

                    'store_id_dynamic' => $request->get('store_id_dynamic'),

                    'client_id_dynamic' => $request->get('snap_client_id_dynamic'),
                    'client_secret_dynamic' => $request->get('snap_client_secret_dynamic'),

                    'payment_notify_mode' => $request->get('payment_notify_modal'),
                    'webhook_url' => $request->get('webhook_url'),
                ]
            ]);

            if ($response->status_code == '7999'){
                H::flashFailed($response->status_message, true);
                return redirect(route("yukk_co.qris_setting.detail", $branch_id));
            }else if ($response->is_ok){
                H::flashSuccess($response->status_message, true);
                return redirect(route("yukk_co.qris_setting.detail", $branch_id));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function updateData(Request $request, $branch_id)
    {
        $access_control = "QRIS_MENU.MANAGE_QRIS.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_UPDATE_QRIS_SETTING_YUKK_CO, [
                'form_params' => [
                    'edc_dynamic' => $request->get('edc-dynamic-id'),
                    'payment_notify_mode_dynamic' => $request->get('payment_notify_mode_dynamic'),
                    'webhook_url_dynamic' => $request->get('webhook_url_dynamic'),
                    'edc_static' => $request->get('edc-static'),
                    'payment_notify_mode_static' => $request->get('payment_notify_mode_static'),
                    'webhook_url_static' => $request->get('webhook_url_static'),
                    'branch_id' => $branch_id,
                    'partner_order_timeout' => $request->get('partner_order_timeout'),
                    'partner_order_id_max_payment' => $request->get('partner_order_id_max_payment'),
                    'partner_order_id_prefix' => $request->get('partner_order_id_prefix'),
                    'partner_order_id_length' => $request->get('partner_order_id_length'),
                ]
            ]);

            if ($response->is_ok){
                H::flashSuccess($response->status_message, true);
                return redirect(route("yukk_co.qris_setting.detail", $response->result->id));
            }else{
                H::flashFailed($response->status_message, true);
                return redirect()->back();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}

