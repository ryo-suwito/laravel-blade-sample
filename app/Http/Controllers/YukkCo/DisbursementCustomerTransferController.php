<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 12-Oct-22
 * Time: 15:52
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisbursementCustomerTransferController extends BaseController {

    public function action(Request $request, $disbursement_customer_transfer_id) {
        $access_control = "DISBURSEMENT_CUSTOMER_TRANSFER.ACTION";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "status" => "required",
            "action" => "required",
            "transfer_using" => "required",
        ]);
        $validator->validate();

        $form_params = [
            "disbursement_customer_transfer_id" => $disbursement_customer_transfer_id,
            "status" => $request->get("status"),
            "action" => $request->get("action"),
            "transfer_using" => $request->get("transfer_using"),
        ];

        if ($request->has("status_flip")) {
            $form_params['status_flip'] = $request->get("status_flip");
        }
        $disbursement_customer_transfer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_TRANSFER_ACTION_YUKK_CO, [
            "form_params" => $form_params,
        ]);

        if (! $disbursement_customer_transfer_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_transfer_response);
        }


        S::flashSuccess($disbursement_customer_transfer_response->status_message, true);
        return redirect(route("cms.yukk_co.disbursement_customer.item", $disbursement_customer_transfer_response->result->disbursement_customer_master_id));
    }

}