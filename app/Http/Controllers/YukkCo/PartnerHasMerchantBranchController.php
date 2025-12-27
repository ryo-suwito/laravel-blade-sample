<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 17-Dec-21
 * Time: 11:18
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerHasMerchantBranchController extends BaseController {

    public function index(Request $request, $partner_id) {
        $access_control = "PARTNER_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $filter_branch_name = $request->get("merchant_branch_name", "");
            $filter_active = $request->get("active", -1);

            $form_params = [
                "partner_id" => $partner_id,
            ];
            if ($filter_branch_name) {
                $form_params["merchant_branch_name"] = $filter_branch_name;
            }
            if ($filter_active != -1) {
                $form_params["active"] = $filter_active;
            }
            $partner_has_merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_LIST_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            //dd($partner_has_merchant_branch_response);

            if ($partner_has_merchant_branch_response->is_ok) {
                $result = $partner_has_merchant_branch_response->result;

                return view("yukk_co.partner_has_merchant_branch.list", [
                    "partner" => $result,
                    "filter_branch_name" => $filter_branch_name,
                    "filter_active" => $filter_active,
                ]);
            } else if ($partner_has_merchant_branch_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_has_merchant_branch_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    // public function create(Request $request, $partner_id) {
    //     $access_control = "PARTNER_UPDATE";
    //     if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
    //         $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_ITEM_YUKK_CO, [
    //             "form_params" => [
    //                 "partner_id" => $partner_id,
    //             ],
    //         ]);

    //         if ($partner_response->is_ok) {
    //             $filter_branch_name = $request->get("merchant_branch_name", "");
    //             $filter_active = $request->get("active", -1);
    //             $filter_company_status_legal = $request->get("company_status_legal", "ALL");

    //             $form_params = [
    //                 "partner_id" => $partner_id,
    //             ];
    //             if ($filter_branch_name) {
    //                 $form_params["merchant_branch_name"] = $filter_branch_name;
    //             }
    //             if ($filter_active != -1) {
    //                 $form_params["active"] = $filter_active;
    //             }
    //             if ($filter_company_status_legal != "ALL") {
    //                 $form_params["company_status_legal"] = $filter_company_status_legal;
    //             }

    //             $merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_MERCHANT_BRANCH_LIST_NOT_IN_PARTNER_HAS_MERCHANT_BRANCH_YUKK_CO, [
    //                 "form_params" => $form_params,
    //                 "query" => [
    //                     "per_page" => 20,
    //                     "page" => $request->get("page", "1"),
    //                 ]
    //             ]);

    //             if ($merchant_branch_response->is_ok) {
    //                 $partner = $partner_response->result;
    //                 $merchant_branch_list = $merchant_branch_response->result->data;

    //                 $current_page = $merchant_branch_response->result->current_page;
    //                 $last_page = $merchant_branch_response->result->last_page;
    //                 return view("yukk_co.partner_has_merchant_branch.create", [
    //                     "partner" => $partner,
    //                     "merchant_branch_list" => $merchant_branch_list,
    //                     "current_page" => $current_page,
    //                     "last_page" => $last_page,
    //                     "filter_branch_name" => $filter_branch_name,
    //                     "filter_active" => $filter_active,
    //                     "filter_company_status_legal" => $filter_company_status_legal,
    //                 ]);
    //             } else {
    //                 return $this->getApiResponseNotOkDefaultResponse($merchant_branch_response);
    //             }
    //         } else {
    //             return $this->getApiResponseNotOkDefaultResponse($partner_response);
    //         }
    //     } else {
    //         return abort(401, __("cms.401_unauthorized_message", [
    //             "access_contol_list" => $access_control,
    //         ]));
    //     }
    // }

    // public function store(Request $request, $partner_id) {
    //     $access_control = "PARTNER_UPDATE";
    //     if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
    //         $validator = Validator::make($request->all(), [
    //             "partner_id" => "required",
    //             "merchant_branch_ids" => "required|array",
    //         ]);
    //         if (! $validator->fails()) {
    //             $partner_id = $request->get("partner_id");
    //             $merchant_branch_ids = $request->get("merchant_branch_ids");

    //             $form_params = [
    //                 "partner_id" => $partner_id,
    //                 "merchant_branch_ids" => $merchant_branch_ids,
    //             ];
    //             $partner_has_merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_CREATE_YUKK_CO, [
    //                 "form_params" => $form_params,
    //             ]);

    //             if ($partner_has_merchant_branch_response->is_ok) {
    //                 H::flashSuccess($partner_has_merchant_branch_response->status_message, true);
    //                 return back();
    //             } else {
    //                 return $this->getApiResponseNotOkDefaultResponse($partner_has_merchant_branch_response);
    //             }
    //         } else {
    //             H::flashFailed($validator->errors()->first(), true);
    //             return back()->withInput();
    //         }
    //     } else {
    //         return abort(401, __("cms.401_unauthorized_message", [
    //             "access_contol_list" => $access_control,
    //         ]));
    //     }
    // }

    // public function edit(Request $request, $partner_id) {
    //     $access_control = "PARTNER_UPDATE";
    //     if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

    //         $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_ITEM_YUKK_CO, [
    //             "form_params" => [
    //                 "partner_id" => $partner_id,
    //             ],
    //         ]);

    //         if ($partner_response->is_ok) {

    //             $filter_branch_name = $request->get("merchant_branch_name", "");
    //             $filter_active = $request->get("active", -1);

    //             $form_params = [
    //                 "partner_id" => $partner_id,
    //             ];
    //             if ($filter_branch_name) {
    //                 $form_params["merchant_branch_name"] = $filter_branch_name;
    //             }
    //             if ($filter_active != -1) {
    //                 $form_params["active"] = $filter_active;
    //             }
    //             $partner_has_merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_PURE_LIST_YUKK_CO, [
    //                 "form_params" => $form_params,
    //                 "query" => [
    //                     "per_page" => 20,
    //                     "page" => $request->get("page", "1"),
    //                 ]
    //             ]);

    //             //dd($partner_has_merchant_branch_response);

    //             if ($partner_has_merchant_branch_response->is_ok) {
    //                 $partner_has_merchant_branch_list = $partner_has_merchant_branch_response->result->data;

    //                 $current_page = $partner_has_merchant_branch_response->result->current_page;
    //                 $last_page = $partner_has_merchant_branch_response->result->last_page;
    //                 return view("yukk_co.partner_has_merchant_branch.edit", [
    //                     "partner" => $partner_response->result,
    //                     "partner_has_merchant_branch_list" => $partner_has_merchant_branch_list,
    //                     "filter_branch_name" => $filter_branch_name,
    //                     "filter_active" => $filter_active,
    //                     "current_page" => $current_page,
    //                     "last_page" => $last_page,
    //                 ]);
    //             } else if ($partner_has_merchant_branch_response->status_code == 7014) {
    //                 return abort(404);
    //             } else {
    //                 return $this->getApiResponseNotOkDefaultResponse($partner_has_merchant_branch_response);
    //             }

    //         } else {
    //             return $this->getApiResponseNotOkDefaultResponse($partner_response);
    //         }
    //     } else {
    //         return abort(401, __("cms.401_unauthorized_message", [
    //             "access_contol_list" => $access_control,
    //         ]));
    //     }
    // }

    // public function update(Request $request, $partner_id) {
    //     $access_control = "PARTNER_UPDATE";
    //     if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
    //         $want_to_delete_merchant_branch_ids = [];
    //         foreach ($request->get("action_merchant_branch") as $merchant_branch_id => $value) {
    //             if ($value == 0) {
    //                 $want_to_delete_merchant_branch_ids[] = $merchant_branch_id;
    //             }
    //         }

    //         $form_params = [
    //             "partner_id" => $partner_id,
    //             "delete_merchant_branch_ids" => $want_to_delete_merchant_branch_ids,
    //         ];

    //         $partner_has_merchant_branch_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PARTNER_HAS_MERCHANT_BRANCH_DELETE_YUKK_CO, [
    //             "form_params" => $form_params,
    //         ]);

    //         //dd($partner_has_merchant_branch_response);

    //         if ($partner_has_merchant_branch_response->is_ok) {
    //             H::flashSuccess($partner_has_merchant_branch_response->status_message, true);
    //             return back();
    //         } else {
    //             return $this->getApiResponseNotOkDefaultResponse($partner_has_merchant_branch_response);
    //         }
    //     } else {
    //         return abort(401, __("cms.401_unauthorized_message", [
    //             "access_contol_list" => $access_control,
    //         ]));
    //     }
    // }

}
