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

class DTTOTController extends BaseController
{
    public function index(Request $request) {
        $access_control = "DTTOT.VIEW";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $filter = $request->only(['search', 'type', 'status', 'page', 'per_page']);
            $page = $request->get("page", 1);
            $query_params = [
                "page" => $page,
                "per_page" => $filter['per_page'] ?? 10,
                'type' => $filter['type'] ?? '',
                'status' => $filter['status'] ?? '',
                'search' => $filter['search'] ?? '',
            ];

            $dttot_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_DTTOT_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);
            if($dttot_response->status_code == 200) {
                $result = $dttot_response->result;
                $dttot_list = $result->data;
                $dttot_list = json_decode(json_encode($dttot_list), true);
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                return view("yukk_co.dttot.list", [
                    "dttot_list" => $dttot_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "total" => $result->total,
                    "filter" => $filter,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($dttot_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function approvalList(Request $request) {
        $access_control = "DTTOT_APPROVAL.VIEW";
        $can_edit = AccessControlHelper::checkCurrentAccessControl("DTTOT_APPROVAL.UPDATE", "AND");
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $filter = $request->only(['search', 'status', 'request', 'page', 'per_page']);
            $page = $request->get("page", 1);
            $query_params = [
                "page" => $page,
                "per_page" => $filter['per_page'] ?? 10,
                'status' => $filter['status'] ?? '',
                'action' => $filter['request'] ?? '',
                'search' => $filter['search'] ?? '',
            ];
            $dttot_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_DTTOT_APPROVAL_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);
            if($dttot_response->status_code == 200) {
                $result = $dttot_response->result;
                $dttot_list = $result->data;
                $dttot_list = json_decode(json_encode($dttot_list), true);
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                return view("yukk_co.dttot_approval.list", [
                    "users" => $dttot_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "total" => $result->total,
                    "filter" => $filter,
                    "can_edit" => $can_edit
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($dttot_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
    
    public function toggleStatus(Request $request) {
        $access_control = "DTTOT_APPROVAL.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $selectedIds = $request->get('ids', '');
            $approveOrReject = $request->get('approveOrReject');
            if(!$selectedIds) {
                H::flashError("Please select at least one data.", true);
                return redirect()->back();
            }
            try {
                if(!is_array($selectedIds)) {
                    $selectedIds = explode(",", $selectedIds);
                }
            } catch (\Exception $e) {
                \Log::error($e);
                H::flashError("Error while parsing selected ids.", true);
                return redirect()->back();
            }
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DTTOT_APPROVAL_ACTION_YUKK_CO, [
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
                if($response->status_code == 500) {
                    H::flashFailed("Internal Server Error", true);
                } else if(!isset($response->status_message) || !$response->status_message) {
                    H::flashFailed("Failed to update data. " . "Status Code: " . $response->status_code, true);
                } else {
                    H::flashFailed($response->status_message, true);
                }
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $id) {
        $access_control = "DTTOT.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $dttot_detail_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_DTTOT_DETAIL_YUKK_CO . $id , []);
            if($dttot_detail_response->status_code == 200) {
                $dttot_detail =$dttot_detail_response->result;
                $dttot_detail = json_decode(json_encode($dttot_detail), true);
                return view("yukk_co.dttot.edit", [
                    "user" => $dttot_detail,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($dttot_detail_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $id) {
        $access_control = "DTTOT.VIEW";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $dttot_detail_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_DTTOT_DETAIL_YUKK_CO . $id , []);
            if($dttot_detail_response->status_code == 200) {
                $dttot_detail =$dttot_detail_response->result;
                $dttot_detail = json_decode(json_encode($dttot_detail), true);
                return view("yukk_co.dttot.detail", [
                    "user" => $dttot_detail,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($dttot_detail_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function approvalDetail(Request $request, $id) {
        $access_control = "DTTOT_APPROVAL.VIEW";
        $can_edit = AccessControlHelper::checkCurrentAccessControl("DTTOT_APPROVAL.UPDATE", "AND");
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $dttot_detail_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_DTTOT_APPROVAL_DETAIL_YUKK_CO . $id , []);
            if($dttot_detail_response->status_code == 200) {
                $dttot_detail =$dttot_detail_response->result;
                $dttot_detail = json_decode(json_encode($dttot_detail), true);
                if(!isset($dttot_detail['payloads']) || !$dttot_detail['payloads']) {
                    $dttot_detail['payloads'] = @$dttot_detail['reference'];
                }
                return view("yukk_co.dttot_approval.detail", [
                    "user" => $dttot_detail,
                    "can_edit" => $can_edit
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($dttot_detail_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(UpdateRequest $request, $id) {
        $access_control = "DTTOT.UPDATE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $request_params = $request->all();
            $identities = [];
            if(!isset($request_params['identities_type']) || !isset($request_params['identities_value'])) {
                $request_params['identities_type'] = [];
                $request_params['identities'] = [];
            }
            foreach ($request_params['identities_type'] as $key => $identity_type) {
                $identity_type_index = array_search($identity_type, array_column($identities, 'type'));
                if($identity_type_index !== false) {
                    $identities[$identity_type_index]['values'][] = $request_params['identities_value'][$key];
                } else {
                    $identities[] = [
                        'type' => $identity_type,
                        'values' => [$request_params['identities_value'][$key]],
                    ];
                }
            }
    
            $request_params['identities'] = $identities;
            // if not set aliases then set aliases to empty array
            if(!isset($request_params['aliases'])) {
                $request_params['aliases'] = [];
            }
            $response = ApiHelper::requestGeneral("PUT", ApiHelper::END_POINT_DTTOT_UPDATE_YUKK_CO . $id, [
                'json' => $request_params,
                "headers" =>
                    [
                        "Accept" => "application/json"
                    ]
            ]);
            if ($response->status_code != 200) {
                if (isset($response->status_message) && $response->status_message) {
                    H::flashFailed($response->status_message, true);
                } else {
                    H::flashFailed("Failed to update data. " . "Status Code: " . $response->status_code, true);
                }
                return back()->withInput();
            }
            H::flashSuccess($response->status_message, true);
            return redirect(route('cms.yukk_co.dttot.detail', $id));
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function delete(Request $request, $id) {
        $access_control = "DTTOT.DELETE";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $response = ApiHelper::requestGeneral("DELETE", ApiHelper::END_POINT_DTTOT_DELETE_YUKK_CO . $id, [
                "headers" =>
                    [
                        "Accept" => "application/json"
                    ]
            ]);
    
            if($response->status_code == 200) {
                $result = $response->result;
                $result = json_decode(json_encode($result), true);
                H::flashSuccess("Data will be deleted after request is approved", true);
                return back();
            } else {
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

    }

    public function import(Request $request) {
        $access_control = "DTTOT.ADD";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DTTOT_IMPORT_YUKK_CO, [
                "headers" => [
                    "Accept" => "application/json"
                ]
            ]);
            \Log::info(json_encode($response));
            if($response->status_code == 200) {
                $result = $response->result;
                $result = json_decode(json_encode($result), true);
                if($response->status_message){
                    H::flashSuccess($response->status_message, true);
                } else {
                    H::flashSuccess("Successfully importing data.", true);
                }
                return redirect(route('cms.yukk_co.dttot.list'));
            } else {
                if($response->status_code == 500) {
                    H::flashFailed("Internal Server Error", true);
                } else if(!isset($response->status_message) || !$response->status_message) {
                    H::flashFailed("Failed to import data. " . "Status Code: " . $response->status_code, true);
                } else {
                    H::flashFailed($response->status_message, true);
                }
                return redirect(route('cms.yukk_co.dttot.list'));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
    
    // import preview call api post same as import, but return response to view
    public function importPreview(Request $request) {
        // check method post or get
        $access_control = "DTTOT.ADD";
        if(AccessControlHelper::checkCurrentAccessControl($access_control, "AND")){
            if($request->isMethod('post')) {
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DTTOT_IMPORT_PREVIEW_YUKK_CO, [
                    "multipart" => [
                        [
                            "name" => "file",
                            "contents" => $request->file('file')->getContent(),
                            "filename" => $request->file('file')->getClientOriginalName()
                        ]
                    ],
                    "headers" =>
                        [
                            "Accept" => "application/json"
                        ]
                ]);
        
                if($response->status_code == 200) {
                    $result = $response->result;
                    $allCount = $result->all_result_count;
                    $failedCount = $result->failed_result_count;
                    $successCount = $allCount - $failedCount;
                    $result = json_decode(json_encode($result), true);
                    H::flashSuccess($response->status_message, true);
                    return view("yukk_co.dttot.preview", [
                        "dttot_list" => $result['result']['data'],
                        "allCount" => $allCount,
                        "successCount" => $successCount,
                        "current_page" => 1,
                        "last_page" => $result['result']['last_page'],
                        "filter" => [],
                        "files" => $request->file('file')
                    ]);
                } else {
                    H::flashFailed($response->status_message, true);
                    return $this->getApiResponseNotOkDefaultResponse($response);
                }
            }
            $filter_status = request()->get("filter_status", '');
            // get page from url param
            $query_params = [
                "page" => $request->get("page", 1),
                "per_page" => request()->get("per_page", 10),
                "filter_status" => $filter_status,
            ];
            // hit api import preview as get request and return response to view
            $dttot_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DTTOT_IMPORT_PREVIEW_YUKK_CO, [
                "query" => $query_params,
            ]);
    
            if($dttot_response->status_code == 200) {
                $result = $dttot_response->result;
                $allCount = $result->all_result_count;
                $failedCount = $result->failed_result_count;
                $successCount = $allCount - $failedCount;
                $result = json_decode(json_encode($result), true);
                return view("yukk_co.dttot.preview", [
                    "dttot_list" => $result['result']['data'],
                    "allCount" => $allCount,
                    "successCount" => $successCount,
                    "current_page" => $result['result']['current_page'],
                    "last_page" => $result['result']['last_page'],
                    "filter" => [
                        "filter_status" => $filter_status
                    ]
                ]);
            } else {
                H::flashFailed($dttot_response->status_message, true);
                return $this->getApiResponseNotOkDefaultResponse($dttot_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    // download_template excel as sample in public folder
    public function downloadTemplate(Request $request) {
        $file_path = public_path('template/dttot_import_template.xlsx');
        return response()->download($file_path);
    }

}
