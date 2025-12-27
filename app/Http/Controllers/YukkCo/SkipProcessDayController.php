<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 01-Mar-22
 * Time: 15:22
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkipProcessDayController extends BaseController {

    public function index(Request $request) {
        $access_control = "SKIP_PROCESS_DAY_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $query_params = [
                "page" => $page,
                "per_page" => 10000,
            ];

            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);
            //dd($skip_process_day_response);

            if ($skip_process_day_response->is_ok) {
                $result = $skip_process_day_response->result;

                $skip_process_day_list = $result->data;

                $current_page = $result->current_page;
                $last_page = $result->last_page;

                return view("yukk_co.skip_process_days.list", [
                    "skip_process_day_list" => $skip_process_day_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $skip_process_day_id) {
        $access_control = "SKIP_PROCESS_DAY_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_ITEM_YUKK_CO, [
                "form_params" => [
                    "skip_process_day_id" => $skip_process_day_id,
                ],
            ]);
            //dd($skip_process_day_response);

            if ($skip_process_day_response->is_ok) {
                $result = $skip_process_day_response->result;

                $skip_process_day = $result;

                return view("yukk_co.skip_process_days.show", [
                    "skip_process_day" => $skip_process_day,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request) {
        $access_control = "SKIP_PROCESS_DAY_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            return view("yukk_co.skip_process_days.create", [
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request) {
        $access_control = "SKIP_PROCESS_DAY_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "date" => "required|date_format:d-M-Y",
                "title" => "required",
                "description" => "required",
            ]);
            $validator->validate();


            $date_string = $request->get("date");
            $title = $request->get("title");
            $description = $request->get("description");

            try {
                $date = Carbon::parse($date_string);
            } catch (\Exception $e) {
                $date = Carbon::now()->startOfDay();
            }

            $form_params = [
                "date" => $date->format("Y-m-d"),
                "title" => $title,
                "description" => $description,
            ];

            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_CREATE_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($skip_process_day_response->is_ok) {
                H::flashSuccess($skip_process_day_response->status_message, true);

                return redirect(route("cms.yukk_co.skip_process_day.item", @$skip_process_day_response->result->id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $skip_process_day_id) {
        $access_control = "SKIP_PROCESS_DAY_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_ITEM_YUKK_CO, [
                "form_params" => [
                    "skip_process_day_id" => $skip_process_day_id,
                ],
            ]);
            //dd($skip_process_day_response);

            if ($skip_process_day_response->is_ok) {
                $result = $skip_process_day_response->result;

                $skip_process_day = $result;

                return view("yukk_co.skip_process_days.edit", [
                    "skip_process_day" => $skip_process_day,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $skip_process_day_id) {
        $access_control = "SKIP_PROCESS_DAY_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "date" => "required|date_format:d-M-Y",
                "title" => "required",
                "description" => "required",
            ]);
            $validator->validate();


            $date_string = $request->get("date");
            $title = $request->get("title");
            $description = $request->get("description");

            try {
                $date = Carbon::parse($date_string);
            } catch (\Exception $e) {
                $date = Carbon::now()->startOfDay();
            }

            $form_params = [
                "skip_process_day_id" => $skip_process_day_id,
                "date" => $date->format("Y-m-d"),
                "title" => $title,
                "description" => $description,
            ];

            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_EDIT_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($skip_process_day_response->is_ok) {
                H::flashSuccess($skip_process_day_response->status_message, true);

                return redirect(route("cms.yukk_co.skip_process_day.item", @$skip_process_day_response->result->id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function delete(Request $request, $skip_process_day_id) {
        $access_control = "SKIP_PROCESS_DAY_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $form_params = [
                "skip_process_day_id" => $skip_process_day_id,
            ];

            $skip_process_day_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SKIP_PROCESS_DAY_DELETE_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($skip_process_day_response->is_ok) {
                H::flashSuccess($skip_process_day_response->status_message, true);

                return redirect(route("cms.yukk_co.skip_process_day.list"));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($skip_process_day_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}