<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 16-Jan-23
 * Time: 18:18
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class PartnerWebhookController extends BaseController {

    public function resendDynamicForm(Request $request) {
        $access_control = "PARTNER_WEBHOOK.RESEND_DYNAMIC";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $data = [];
        return view("yukk_co.partner_webhook.resend_webhook_dynamic", $data);
    }

    public function resendDynamicProcess(Request $request) {
        $access_control = "PARTNER_WEBHOOK.RESEND_DYNAMIC";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $rrn_strings = $request->get("rrn_list");
        $dirty_rrn_list = explode("\n", $rrn_strings);
        $rrn_list = [];
        foreach ($dirty_rrn_list as $rrn) {
            $rrn = preg_replace('/[^a-zA-Z0-9]/', "", $rrn);
            $rrn_list[] = $rrn;
        }

        $resend_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_CORE_V2_PARTNER_WEBHOOK_RESEND_DYNAMIC, [
            "form_params" => [
                "rrn_list" => $rrn_list,
                "skey" => env("PARTNER_WEBHOOK_CORE_API_KEY"),
            ],
        ]);

        if ($resend_response->is_ok) {
            $result_rrn_list = $resend_response->result;

            $not_found_rrn_list = collect($rrn_list)->diff(collect($result_rrn_list));

            $data = [
                "status_message" => $resend_response->status_message,
                "rrn_list" => $result_rrn_list,
                "not_found_rrn_list" => $not_found_rrn_list,
            ];

            return view("yukk_co.partner_webhook.success", $data);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($resend_response);
        }
    }

    public function resendStaticForm(Request $request) {
        $access_control = "PARTNER_WEBHOOK.RESEND_STATIC";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $data = [];
        return view("yukk_co.partner_webhook.resend_webhook_static", $data);
    }

    public function resendStaticProcess(Request $request) {
        $access_control = "PARTNER_WEBHOOK.RESEND_STATIC";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $rrn_strings = $request->get("rrn_list");
        $dirty_rrn_list = explode("\n", $rrn_strings);
        $rrn_list = [];
        foreach ($dirty_rrn_list as $rrn) {
            $rrn = preg_replace('/[^a-zA-Z0-9]/', "", $rrn);
            $rrn_list[] = $rrn;
        }

        $resend_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_CORE_V2_PARTNER_WEBHOOK_RESEND_STATIC, [
            "form_params" => [
                "rrn_list" => $rrn_list,
                "skey" => env("PARTNER_WEBHOOK_CORE_API_KEY"),
            ],
        ]);

        if ($resend_response->is_ok) {
            $result_rrn_list = $resend_response->result;

            $not_found_rrn_list = collect($rrn_list)->diff(collect($result_rrn_list));

            $data = [
                "status_message" => $resend_response->status_message,
                "rrn_list" => $result_rrn_list,
                "not_found_rrn_list" => $not_found_rrn_list,
            ];

            return view("yukk_co.partner_webhook.success", $data);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($resend_response);
        }
    }
}
