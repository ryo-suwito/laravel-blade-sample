<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Jul-22
 * Time: 12:47
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerOrderController extends BaseController {


    public function show(Request $request, $partner_order_id) {
        $access_control = "PARTNER_ORDER.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_order_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_PARTNER_ORDER_ITEM_YUKK_CO, [
            "form_params" => [
                "partner_order_id" => $partner_order_id,
            ],
        ]);


        if (! $partner_order_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_order_response);
        }

        $partner_order = $partner_order_response->result;

        //dd($partner_order_response);
        return view("yukk_co.partner_orders.show", [
            "partner_order" => $partner_order,
        ]);
    }

    public function edit(Request $request, $partner_order_id) {
        $access_control = "PARTNER_ORDER.EDIT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_order_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_PARTNER_ORDER_ITEM_YUKK_CO, [
            "form_params" => [
                "partner_order_id" => $partner_order_id,
            ],
        ]);


        if (! $partner_order_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_order_response);
        }

        $partner_order = $partner_order_response->result;

        //dd($partner_order_response);
        return view("yukk_co.partner_orders.edit", [
            "partner_order" => $partner_order,
        ]);
    }

    public function update(Request $request, $partner_order_id) {
        $access_control = "PARTNER_ORDER.EDIT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "expiry_time" => "required|date_format:d-M-Y H:i:s",
            "max_payment" => "required|integer",
        ]);
        $validator->validate();

        $partner_order_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_JALIN_PARTNER_ORDER_EDIT_YUKK_CO, [
            "form_params" => [
                "partner_order_id" => $partner_order_id,
                "expiry_time" => $request->get("expiry_time"),
                "max_payment" => $request->get("max_payment"),
            ],
        ]);

        if (! $partner_order_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_order_response);
        }

        S::flashSuccess($partner_order_response->status_message, true);
        return redirect(route("cms.yukk_co.partner_order.show", $partner_order_id));
    }

}