<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        $access_control = ["MASTER_DATA.EVENT.VIEW", "MASTER_DATA.EVENT.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $name = $request->get("name", null);
            $code = $request->get("code", null);

            $query_params = [];

            if ($name) {
                $query_params["name"] = $name;
            }
            if ($code){
                $query_params["code"] = $code;
            }

            $access_control = json_decode(S::getUserRole()->role->access_control);

            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($response->is_ok) {
                $event_list = $response->result;
                return view("yukk_co.event.list", [
                    "event_list" => $event_list,
                    "access_control" => $access_control,

                    "name" => $name,
                    "code" => $code
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail($event_id){
        $access_control = ["MASTER_DATA.EVENT.VIEW", "MASTER_DATA.EVENT.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $event_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_EDIT_YUKK_CO, [
                "form_params" => [
                    "id" => $event_id,
                ],
            ]);

            if ($event_response->is_ok){
                $event = $event_response->result;
            }else{
                return $this->getApiResponseNotOkDefaultResponse($event_response);
            }

            return view("yukk_co.event.detail", [
                'event' => $event,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $event_id)
    {
        $access_control = "MASTER_DATA.EVENT.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $event_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_EDIT_YUKK_CO, [
                "form_params" => [
                    "id" => $event_id,
                ],
            ]);

            if ($event_response->is_ok){
                $event = $event_response->result;
            }else{
                return $this->getApiResponseNotOkDefaultResponse($event_response);
            }

            return view("yukk_co.event.edit", [
                'event' => $event,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $event_id)
    {
        $access_control = "MASTER_DATA.EVENT.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $event_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_UPDATE_YUKK_CO, [
                "form_params" => [
                    "id" => $event_id,
                    'name' => $request->get('name'),
                    'code' => $request->get('code'),
                    'short_description' => $request->get('short_description'),
                    'description' => $request->get('description'),
                    'display_status' => $request->get('display_status'),
                    'location' => $request->get('location'),
                    'start_date' => $request->get('start_date'),
                    'end_date' => $request->get('end_date'),
                    'event_organizer_name' => $request->get('event_organizer_name'),
                    'event_organizer_code' => $request->get('event_organizer_code'),
                ],
            ]);

            if ($event_response->is_ok) {
                H::flashSuccess('Data changes are successfully saved and are in the process of being reviewed first', true);
                return redirect(route("yukk_co.event.detail", $event_id));
            } else {
                H::flashFailed($event_response->status_message, true);
                return redirect(route("yukk_co.event.edit", $event_id));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request)
    {
        $access_control = "MASTER_DATA.EVENT.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return view('yukk_co.event.create');
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request)
    {
        $access_control = "MASTER_DATA.EVENT.UPDATE";

        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "code" => "required",
                "short_description" => "required",
                "description" => "required",
                "display_status" => "required",
                "location" => "required",
                "start_date" => "required",
                "end_date" => "required",
                "event_organizer_name" => "required",
                "event_organizer_code" => "required",
            ]);
            $validator->validate();

            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EVENT_STORE_YUKK_CO, [
                "form_params" => [
                    'name' => $request->get('name'),
                    'code' => $request->get('code'),
                    'short_description' => $request->get('short_description'),
                    'description' => $request->get('description'),
                    'display_status' => $request->get('display_status'),
                    'location' => $request->get('location'),
                    'start_date' => $request->get('start_date'),
                    'end_date' => $request->get('end_date'),
                    'event_organizer_name' => $request->get('event_organizer_name'),
                    'event_organizer_code' => $request->get('event_organizer_code'),
                ],
            ]);

            if ($partner_fee_response->is_ok){
                H::flashSuccess('Success Create', true);
                return redirect(route('yukk_co.event.detail', $partner_fee_response->result->id));
            } else {
                H::flashFailed($partner_fee_response->status_message, true);
                return redirect(route("yukk_co.event.create"))->withInput();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
