<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Users\StoreRequest;
use App\Http\Requests\Store\Users\UpdateRequest;
use App\Services\CoreAPI\BeneficiaryService;
use App\Services\CoreAPI\MerchantBranchService;
use App\Services\CoreAPI\PartnerService;
use App\Services\StoreManagement\RoleService;
use App\Services\StoreManagement\TargetIdService;
use App\Services\StoreManagement\UserService;
use App\Services\StoreManagement\UserSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;
use Termwind\Components\Dd;

class SuspectedUserController extends Controller
{
    public function index(Request $request) {
        $access_control = "SUSPECTED_USERS.VIEW";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $filter = $request->only(['search', 'status', 'date_range', 'page', 'source_type', 'per_page']);
            if(!isset($filter['source_type'])) {
                $filter['source_type'] = 'USER';
            }
            if(!isset($filter['page'])) {
                $filter['page'] = 1;
            }
            if(!isset($filter['status'])) {
                $filter['status'] = '';
            }
            if(!isset($filter['search'])) {
                $filter['search'] = '';
            }
            if(!isset($filter['per_page'])) {
                $filter['per_page'] = 10;
            }
            // start at (date_range)
            if (isset($filter['date_range'])) {
                $date_range = explode(' - ', $filter['date_range']);
                $filter['start_at'] = $date_range[0];
                $filter['end_at'] = $date_range[1];
            } else {
                $filter['start_at'] = '';
                $filter['end_at'] = '';
                $filter['date_range'] = '';
            }
            // convert from 01-Apr-2023 00:00:00 to 2023-04-01 00:00:00"
            if (isset($filter['start_at']) && $filter['start_at']) {
                $filter['start_at'] = date('Y-m-d H:i', strtotime($filter['start_at']));
            }
            if (isset($filter['end_at']) && $filter['end_at']) {
                $filter['end_at'] = date('Y-m-d H:i', strtotime($filter['end_at']));
            }
            $users_response = ApiHelper::requestGeneral(
                'GET',
                ApiHelper::END_POINT_SUSPECTED_USERS_LIST_YUKK_CO,
                [
                    'query' => $filter ?? [],
                ]
            );
    
            $users = $users_response->result->data;
            $current_page = $users_response->result->current_page;
            $last_page = $users_response->result->last_page;
            $data = [
                'users' => $users,
                'current_page' => $current_page,
                'last_page' => $last_page,
                'total' => $users_response->result->total,
                'search' => $filter['search'] ? $filter['search'] : '',
                'status' => $filter['status'] ? $filter['status'] : '',
                'source_type' => $filter['source_type'] ? $filter['source_type'] : 'USER',
                'date_range' => $filter['date_range'] ? $filter['date_range'] : '',
            ];
            $data = json_decode(json_encode($data), true);
            return view('yukk_co.suspected_user.list', $data);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    // suspected user approval index
    public function approvalIndex(Request $request) {
        $access_control = "SUSPECTED_USERS_APPROVAL.VIEW";
        $can_edit = AccessControlHelper::checkCurrentAccessControl("SUSPECTED_USERS_APPROVAL.UPDATE", "AND");
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $filter = $request->only(['search', 'status', 'date_range', 'page', 'source_type', 'action', 'per_page']);
            if(!isset($filter['source_type'])) {
                $filter['source_type'] = 'USER';
            }
            if(!isset($filter['page'])) {
                $filter['page'] = 1;
            }
            if(!isset($filter['status'])) {
                $filter['status'] = '';
            }
            if(!isset($filter['search'])) {
                $filter['search'] = '';
            }
            if(!isset($filter['action'])) {
                $filter['action'] = '';
            }
            if(!isset($filter['per_page'])) {
                $filter['per_page'] = 10;
            }
            // start at (date_range)
            if (isset($filter['date_range'])) {
                $date_range = explode(' - ', $filter['date_range']);
                $filter['start_at'] = $date_range[0];
                $filter['end_at'] = $date_range[1];
            } else {
                $filter['start_at'] = '';
                $filter['end_at'] = '';
                $filter['date_range'] = '';
            }
            // convert from 01-Apr-2023 00:00:00 to 2023-04-01 00:00:00"
            if (isset($filter['start_at']) && $filter['start_at']) {
                $filter['start_at'] = date('Y-m-d H:i', strtotime($filter['start_at']));
            }
            if (isset($filter['end_at']) && $filter['end_at']) {
                $filter['end_at'] = date('Y-m-d H:i', strtotime($filter['end_at']));
            }
            $users_response = ApiHelper::requestGeneral(
                'GET',
                ApiHelper::END_POINT_SUSPECTED_USERS_APPROVAL_LIST_YUKK_CO,
                [
                    'query' => $filter ?? [],
                ]
            );
    
            $users = $users_response->result->data;
            $current_page = $users_response->result->current_page;
            $last_page = $users_response->result->last_page;
            $data = [
                'users' => $users,
                'current_page' => $current_page,
                'last_page' => $last_page,
                'total' => $users_response->result->total,
                'search' => $filter['search'] ? $filter['search'] : '',
                'status' => $filter['status'] ? $filter['status'] : '',
                'source_type' => $filter['source_type'] ? $filter['source_type'] : 'USER',
                'date_range' => $filter['date_range'] ? $filter['date_range'] : null,
                'action' => $filter['action'] ? $filter['action'] : '',
                'can_edit' => $can_edit
            ];
            $data = json_decode(json_encode($data), true);
            return view('yukk_co.suspected_user_approvals.list', $data);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $id) {
        $access_control = "SUSPECTED_USERS.VIEW";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $user_response = ApiHelper::requestGeneral(
                'GET',
                ApiHelper::END_POINT_SUSPECTED_USERS_DETAIL_YUKK_CO .$id,
                []
            );
            $user = $user_response->result;
            $data = [
                'user' => $user,
            ];
            // convert data to array
            $data = json_decode(json_encode($data), true);
            return view('yukk_co.suspected_user.detail', $data);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function approvalDetail(Request $request, $id) {
        $access_control = "SUSPECTED_USERS_APPROVAL.VIEW";
        $can_edit = AccessControlHelper::checkCurrentAccessControl("SUSPECTED_USERS_APPROVAL.UPDATE", "AND");
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $user_response = ApiHelper::requestGeneral(
                'GET',
                ApiHelper::END_POINT_SUSPECTED_USERS_APPROVAL_DETAIL_YUKK_CO .$id,
                []
            );
            $user = $user_response->result;
            $data = [
                'user' => $user,
                'source_type' => $user->reference->source_type,
                'can_edit' => $can_edit
            ];
            // convert data to array
            $data = json_decode(json_encode($data), true);
            return view('yukk_co.suspected_user_approvals.detail', $data);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $id) {
        $access_control = "SUSPECTED_USERS.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            // pass multipart/form-data to api
            $multipart = [
                [
                    'name' => 'id',
                    'contents' => $id,
                ],
                [
                    'name' => 'action',
                    'contents' => $request->get('action'),
                ],
                [
                    'name' => 'reason',
                    'contents' => $request->get('reason'),
                ],
            ];
            if($request->file('file')){
                $count = 0;
                foreach($request->file('file') as $file) {
                    $multipart[] = [
                        'name' => 'file' . $count,
                        'contents' => $file->getContent(),
                        'filename' =>  $file->getClientOriginalName()
                    ];
                    $count++;
                }
            }
            $user_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_SUSPECTED_USERS_ACTION_YUKK_CO, [
                "multipart" => $multipart,
            ]);
            if ($user_response->status_code != 200) {
                \Log::error('error update suspected user', [$user_response]);
                if(!empty($user_response->status_message)) {
                    H::flashFailed($user_response->status_message, true);
                }
                else {
                    H::flashFailed("Failed to update suspected user", true);
                }
                return back();
            }
            else {
                H::flashSuccess("User will be updated after approval", true);
                return redirect()->route('cms.yukk_co.suspected_user.list');
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function approvalUpdate(Request $request) {
        $access_control = "SUSPECTED_USERS_APPROVAL.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $user_response = ApiHelper::requestGeneral(
                'POST',
                ApiHelper::END_POINT_SUSPECTED_USERS_ACTION_YUKK_CO,
                [
                    "json" => [
                        'ids' => array_values($request->get('ids')),
                        'action' => $request->get('action')
                    ]
                ]
            );
            if($user_response->status_code != 200) {
                \Log::error('error update suspected user', [$user_response]);
                if(!empty($user_response->status_message)) {
                    H::flashFailed($user_response->status_message, true);
                }
                else {
                    H::flashFailed("Failed to update suspected user", true);
                }
                return back();
            }
            else {
                H::flashSuccess("User updated successfully", true);
                return redirect()->route('cms.yukk_co.suspected_user_approval.list');
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

        
    public function toggleStatus(Request $request) {
        $access_control = "SUSPECTED_USERS_APPROVAL.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $selectedIds = $request->get('ids');
            if(!$selectedIds) {
                H::flashError("Please select at least one data.", true);
                return redirect()->back();
            }
            $approveOrReject = $request->get('approveOrReject');
    
            try {
                if(!is_array($selectedIds)) {
                    $selectedIds = explode(",", $selectedIds);
                }
            } catch (\Exception $e) {
                \Log::error($e);
                H::flashError("Error while parsing selected ids.", true);
                return redirect()->back();
            }
            
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_SUSPECTED_USERS_APPROVAL_ACTION_YUKK_CO, [
                "json" => [
                    "action" => $approveOrReject,
                    "ids" => $selectedIds,
                ],
                "headers" => [
                    "Accept" => "application/json"
                ]
            ]);
    
            if($response->status_code == 200) {
                $result = $response->result;
                $result = json_decode(json_encode($result), true);
                H::flashSuccess("Successfully updating data.", true);
                return redirect()->back();
            } else {
                if(!empty($response->status_message)) {
                    H::flashFailed($response->status_message, true);
                } else {
                    H::flashFailed("Failed to update data.", true);
                }
                return redirect()->back();
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
