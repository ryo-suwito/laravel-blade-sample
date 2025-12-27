<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\DisbursementHelper;
use App\Helpers\H;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("SPLIT_DISBURSEMENT_UPDATE", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "SPLIT_DISBURSEMENT_UPDATE",
                ]));
            }
        });
    }

    public function index(Request $request) {
        $access_control = "SPLIT_DISBURSEMENT_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $disbursement_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_SPLIT_DISBURSEMENT_YUKK_CO, [
            ]);

            if ($disbursement_response->is_ok) {
                $result = $disbursement_response->result;
                $bca_max_transfer_amount = 0;
                $bca_transfer_fraction_amount = 0;
                $non_bca_max_transfer_amount = 0;
                $non_bca_transfer_fraction_amount = 0;

                foreach ($result as $item){
                    if ($item->key == DisbursementHelper::BCA_MAX_TRANSFER_AMOUNT){
                        $bca_max_transfer_amount = $item->value;
                    }else if($item->key == DisbursementHelper::BCA_TRANSFER_FRACTIONS_AMOUNT){
                        $bca_transfer_fraction_amount = $item->value;
                    }else if($item->key == DisbursementHelper::NON_BCA_MAX_TRANSFER_AMOUNT){
                        $non_bca_max_transfer_amount = $item->value;
                    }else if($item->key == DisbursementHelper::NON_BCA_TRANSFER_FRACTIONS_AMOUNT){
                        $non_bca_transfer_fraction_amount = $item->value;
                    }
                }

                return view("yukk_co.disbursement.setting", [
                    "bca_max_transfer_amount" => $bca_max_transfer_amount,
                    "bca_transfer_fraction_amount" => $bca_transfer_fraction_amount,
                    "non_bca_max_transfer_amount" => $non_bca_max_transfer_amount,
                    "non_bca_transfer_fraction_amount" => $non_bca_transfer_fraction_amount,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($disbursement_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request) {
        $access_control = "SPLIT_DISBURSEMENT_UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $bca_transfer_amount =  filter_var($request->get("bca_max_transfer_amount"), FILTER_SANITIZE_NUMBER_INT);
            $bca_transfer_fractions = filter_var($request->get("bca_transfer_fraction_amount"), FILTER_SANITIZE_NUMBER_INT);
            $non_bca_transfer_amount =  filter_var($request->get("non_bca_max_transfer_amount"), FILTER_SANITIZE_NUMBER_INT);
            $non_bca_transfer_fractions = filter_var($request->get("non_bca_max_transfer_fraction_amount"), FILTER_SANITIZE_NUMBER_INT);


            if ($bca_transfer_amount == null || $bca_transfer_fractions == null || $non_bca_transfer_amount == null || $non_bca_transfer_fractions == null){
                H::flashFailed("Value harus diisi dengan angka dan tidak boleh kosong.", true);

                return redirect(route("cms.yukk_co.disbursement.setting"));
            }
            if ($bca_transfer_fractions >= $bca_transfer_amount*0.5 && $bca_transfer_fractions <= $bca_transfer_amount*0.9 && $non_bca_transfer_fractions >= $non_bca_transfer_amount*0.5 && $non_bca_transfer_fractions <= $non_bca_transfer_amount*0.9){

                $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_EDIT_SPLIT_DISBURSEMENT_YUKK_CO, [
                    "form_params" => [
                        "BCA_MAX_TRANSFER_AMOUNT" => (filter_var($request->get("bca_max_transfer_amount"), FILTER_SANITIZE_NUMBER_FLOAT)*0.01),
                        "BCA_TRANSFER_FRACTIONS_AMOUNT" => (filter_var($request->get("bca_transfer_fraction_amount"), FILTER_SANITIZE_NUMBER_FLOAT)*0.01),
                        "NON_BCA_MAX_TRANSFER_AMOUNT" => (filter_var($request->get("non_bca_max_transfer_amount"), FILTER_SANITIZE_NUMBER_FLOAT)*0.01),
                        "NON_BCA_TRANSFER_FRACTIONS_AMOUNT" => (filter_var($request->get("non_bca_max_transfer_fraction_amount"), FILTER_SANITIZE_NUMBER_FLOAT)*0.01),
                    ],
                ]);
            } else{
                H::flashFailed("Value harus 50%-90% dari nilai Transfer", true);

                return redirect(route("cms.yukk_co.disbursement.setting"));
            }

            if ($partner_response->is_ok) {
                H::flashSuccess($partner_response->status_message, true);

                return redirect(route("cms.yukk_co.disbursement.setting"));

            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}
