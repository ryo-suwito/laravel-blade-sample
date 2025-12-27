<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerFeeController extends BaseController
{
    public function index(Request $request) {
        $access_control = ["MASTER_DATA.PARTNER_FEE.VIEW", "MASTER_DATA.PARTNER_FEE.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_LIST_YUKK_CO, []);

            if ($partner_fee_response->is_ok) {
                $partner_fee_list = $partner_fee_response->result;
                $access_control = json_decode(S::getUserRole()->role->access_control);
                return view("yukk_co.partner_fee.list", [
                    "partner_fee_list" => $partner_fee_list,
                    "access_control" => $access_control
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_fee_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail($partner_fee_id) {
        $access_control = ["MASTER_DATA.PARTNER_FEE.VIEW", "MASTER_DATA.PARTNER_FEE.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_EDIT_YUKK_CO, [
                "form_params" => [
                    "id" => $partner_fee_id,
                ],
            ]);

            if ($partner_fee_response->is_ok) {
                return view("yukk_co.partner_fee.detail", [
                    "partner_fee" => $partner_fee_response->result,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_fee_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $partner_fee_id) {
        $access_control = "MASTER_DATA.PARTNER_FEE.UPDATE";
        
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_EDIT_YUKK_CO, [
                "form_params" => [
                    "id" => $partner_fee_id,
                ],
            ]);
            
            $time_threshold_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_TIME_THRESHOLD_YUKK_CO, []);
            $time_threshold = '';
            $time_message = '';
    
            if ($time_threshold_response->is_ok) {
                $time_threshold = $time_threshold_response->result->time_threshold;
                $time_threshold = $time_threshold ? $time_threshold : '23:35';
    
                // Validate that the time_threshold is in the correct "HH:MM" format
                if (!preg_match('/^\d{2}:\d{2}$/', $time_threshold)) {
                    $time_message = 'The scheduled time format is incorrect. Please use "HH:MM".';
                    $time_threshold = ''; // Reset time_threshold to an empty string if format is incorrect
                }
            } else {
                H::flashFailed('Failed to get schedule time.');
                return redirect()->back();
            }
    
            if ($partner_fee_response->is_ok) {
                return view("yukk_co.partner_fee.edit", [
                    "partner_fee" => $partner_fee_response->result,
                    "time_threshold" => $time_threshold,
                    "time_message" => $time_message
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_fee_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
    

    public function update(Request $request, $partner_fee_id)
    {
        $access_control = "MASTER_DATA.PARTNER_FEE.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $schedule_date = $request->get('schedule_date');
            $schedule_time = $request->get('schedule_time');
            $schedule_at = null;
    
            if ($schedule_date && $schedule_time) {
                // Combine date and time into a single timestamp
                $schedule_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $schedule_date . ' ' . $schedule_time);
                $time_threshold_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_TIME_THRESHOLD_YUKK_CO, []);
                
                $time_threshold = $time_threshold_response->result->time_threshold;
                $time_threshold = $time_threshold ? $time_threshold : '23:35';
                // Validate that the time from settings is in the correct "H:i" format
                if (!preg_match('/^\d{2}:\d{2}$/', $time_threshold)) {
                    H::flashFailed('The scheduled time for partner fee is in an incorrect format. Please use "HH:mm".');
                    return redirect()->back();
                }

                if ($time_threshold != $schedule_time){
                    H::flashFailed('Schedule Time does not match, please refresh page and try again');
                    return redirect()->back();
                }
            }

            $schedule_at = $schedule_at ? $schedule_at->format('Y-m-d H:i:s') : null;
            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_UPDATE_YUKK_CO, [
                "form_params" => [
                    "id" => $partner_fee_id,
                    'name' => $request->get('name'),
                    'short_description' => $request->get('short_description'),
                    'description' => $request->get('description'),
                    'display_status' => $request->get('display_status'),
                    'sort_number' => $request->get('sort_number'),
                    'fee_partner_percentage' => $request->get('fee_partner_percentage'),
                    'fee_partner_fixed' => $request->get('fee_partner_fixed'),
                    'fee_yukk_additional_percentage' => $request->get('fee_yukk_additional_percentage'),
                    'fee_yukk_additional_fixed' => $request->get('fee_yukk_additional_fixed'),
                    'schedule_at' => $schedule_at
                ],
            ]);

            if ($partner_fee_response->is_ok) {
                
                if(!$schedule_at) H::flashSuccess('Data changes are successfully saved and are in the process of being reviewed first', true);
                else  H::flashSuccess('Data changes are successfully saved and are in the process of being reviewed first. Once approved, data changes are scheduled to be applied on '. $schedule_at, true);
                return redirect(route("yukk_co.partner_fee.detail", $partner_fee_id));
            } else {
                $partner_fee_response->status_message  = ($partner_fee_response && isset($partner_fee_response->status_message->message))
                        ? $partner_fee_response->status_message->message 
                        : "An error occurred.";
                return $this->getApiResponseNotOkDefaultResponse($partner_fee_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request)
    {
        $access_control = "MASTER_DATA.PARTNER_FEE.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return view('yukk_co.partner_fee.create');
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request){
        $access_control = "MASTER_DATA.PARTNER_FEE.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "name" => "required|max:254",
                "short_description" => "required|max:254",
                "description" => "required|max:254",
                "sort_number" => "required|numeric|min:0",
                "display_status" => "required|in:SHOWN,HIDDEN",
                "fee_partner_percentage" => "required|numeric|min:0|max:100",
                "fee_partner_fixed" => "required|numeric|min:0",
                "fee_yukk_additional_percentage" => "required|numeric|min:0|max:100",
                "fee_yukk_additional_fixed" => "required|numeric|min:0",
            ]);
            $validator->validate();

            $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_STORE_YUKK_CO, [
                "form_params" => [
                    'name' => $request->get('name'),
                    'short_description' => $request->get('short_description'),
                    'description' => $request->get('description'),
                    'display_status' => $request->get('display_status'),
                    'sort_number' => $request->get('sort_number'),
                    'fee_partner_percentage' => $request->get('fee_partner_percentage'),
                    'fee_partner_fixed' => $request->get('fee_partner_fixed'),
                    'fee_yukk_additional_percentage' => $request->get('fee_yukk_additional_percentage'),
                    'fee_yukk_additional_fixed' => $request->get('fee_yukk_additional_fixed'),
                ],
            ]);

            if ($partner_fee_response->is_ok){
                H::flashSuccess('Success Create', true);
                return redirect(route('yukk_co.partner_fee.detail', $partner_fee_response->result->id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_fee_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function getPartnerFeeJSON(Request $request)
    {
        $partner_fee_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_GET_PARTNER_FEE_JSON_YUKK_CO, [
            "query" => [
                "search" => $request->get('search'),
                "per_page" => 10,
                "page" => $request->get('page'),
            ],
        ]);

        if ($partner_fee_response->is_ok){
            $response = array();

            foreach ($partner_fee_response->result->data as $item) {
                $response[] = array(
                    "id" => $item->id,
                    "text" => $item->name
                );
            }

            return response()->json($response);
        }else{
            return response()->json([],400);
        }
    }
}
