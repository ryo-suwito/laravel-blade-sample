<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;

class PartnerLoginController extends BaseController
{
    public function index(Request $request){
        //
        $access_control = ["YUKK_MERCHANT.ACCOUNT_LOGIN.VIEW","YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $page = $request->get("page", 1);
            $per_page = $request->get("per_page", 10);
            $field = $request->get('field');
            $keyword = $request->get('keyword');
            $edc_type = $request->get('edc_type');
            $access_control = json_decode(S::getUserRole()->role->access_control);

            $query_params = [
                "page" => $page,
                "per_page" => $per_page,
                "field" => $field,
                "keyword" => $keyword,
                "edc_type" => $edc_type,
            ];

            $request = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_LIST, [
                'form_params' => $query_params
            ]);

            if ($request->is_ok){
                $from = $request->result->from;
                $to = $request->result->to;
                $total = $request->result->total;

                $current_page = $request->result->current_page;
                $last_page = $request->result->last_page;

                $partner_login_list = $request->result->data;

                return view("yukk_co.partner_login.index", [
                    'partner_login_list' => $partner_login_list,
                    'edc_type' => $query_params['edc_type'],
                    'field' => $query_params['field'],
                    'keyword' => $query_params['keyword'],
                    'access_control' => $access_control,

                    'from' => $from,
                    'to' => $to,
                    'total' => $total,

                    'current_page' => $current_page,
                    'last_page' => $last_page,
                    'per_page' => $per_page
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($request);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $id){
        $access_control = ["YUKK_MERCHANT.ACCOUNT_LOGIN.VIEW","YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_DETAIL, [
                'form_params' => [
                    'id' => $id
                ]
            ]);

            if ($response->is_ok){
                $partner_login_detail = $response->result;
                $scope_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_PARTNER_LOGIN_GET, [
                    'query' => [
                        'partner_login_id' => $id,
                    ]
                ]);

                if ($scope_response->is_ok){
                    $scope_list = $scope_response->result;

                    return view("yukk_co.partner_login.detail", [
                        'partner_login' => $partner_login_detail,
                        'scope_list' => $scope_list,
                    ]);
                }else{
                    return $this->getApiResponseNotOkDefaultResponse($response);
                }
            }else{
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $id){
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_DETAIL, [
                'form_params' => [
                    'id' => $id,
                ]
            ]);

            if ($response->is_ok){
                $result = $response->result;
                $merchant = [
                    'id' => $result->merchant_branch->merchant->id,
                    'name' => $result->merchant_branch->merchant->name
                ];
                
                $old_merchant_id = $result->merchant_branch->merchant_id;
                $merchant_id = $request->get('merchant_id') ? $request->get('merchant_id') : $result->merchant_branch->merchant_id;
                $requested_branch = $request->get('merchant_branch_id');

                $scope_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_PARTNER_LOGIN_GET, [
                    'query' => [
                        'merchant_id' => $merchant_id,
                        'branch_id' => $requested_branch,
                        'partner_login_id' => $id,
                        'qris_type' => $request->get('qris_type')
                    ]
                ]);

                if(!$scope_response->result){
                    H::flashFailed($scope_response->status_message, true);
                    return back();
                }
                
                $branch_list = $scope_response->result->branch_list;
                $branch_id = collect($branch_list)->pluck('id');

                $old = [];
                if ($request->has('merchant_id') || $request->has('merchant_name')){
                    $old['merchant'] = [
                        'id' => $request->get('merchant_id'),
                        'name' => $request->get('merchant_name')
                    ];
                } else {
                    $old['merchant'] = $merchant;
                }
                if ($requested_branch !== null) {
                    $old['merchant_branch_id'] = $requested_branch;
                    $old['merchant_branch_id'] = array_map('intval', (array)$old['merchant_branch_id']);
                } else {
                    $old['merchant_branch_id'] = [];
                }

                if ($scope_response->is_ok){
                    $scope_list = $scope_response->result->selected_branch_list;
                    $scope_id = collect($scope_list)->pluck('edc_id')->toArray();
                    $all_scope = $scope_response->result->branch_list;

                    $count_scope = collect($all_scope)->where('edcs', [])->count() == count($all_scope);

                    return view("yukk_co.partner_login.edit", [
                        'partner_login' => $result,
                        'branch_list' => $branch_list,
                        'scope_id' => $scope_id,
                        'scope_list' => $scope_list,
                        'all_scope' => $all_scope,
                        'count_scope' => $count_scope,

                        'merchant_id' => $merchant_id,
                        'old_merchant_id' => $old_merchant_id,
                        'branch_id' => $branch_id,
                        'id' => $id,
                        'qris_type' => $request->get('qris_type', []),
                        'merchant_branch_ids' => $request->get('merchant_branch_id', []),
                        'merchant' => $merchant,

                        'old' => $old,
                    ]);
                }
            }else{
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function add(Request $request){
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $merchant_id = $request->get('merchant_id', null);
            $requested_branch = $request->get('merchant_branch_id');

            $scope_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_PARTNER_LOGIN_GET, [
                'query' => [
                    'merchant_id' => $merchant_id,
                    'branch_id' => $requested_branch,
                    'qris_type' => $request->get('qris_type')
                ]
            ]);

            if ($scope_response->is_ok){
                if ($scope_response->result){
                    $branch_list = $scope_response->result->branch_list;
                    $branch_id = collect($branch_list)->pluck('id');

                    $scope_list = $scope_response->result->selected_branch_list;
                    $scope_id = collect($scope_list)->pluck('edc_id')->toArray();
                }else{
                    $branch_list = [];
                    $branch_id = 0;

                    $scope_list = [];
                    $scope_id = [];
                }
                $old = [];
                if ($request->has('merchant_id') || $request->has('merchant_name')){
                    $old['merchant'] = [
                        'id' => $request->get('merchant_id'),
                        'name' => $request->get('merchant_name')
                    ];
                } else {
                    $old['merchant'] = [
                        'id' => null,
                        'name' => null
                    ];
                }
                
                $old['merchant_branch_id'] = $requested_branch;
                if($requested_branch){
                    $old['merchant_branch_id'] = array_map('intval', $old['merchant_branch_id']);
                }

                return view("yukk_co.partner_login.create", [
                    'branch_list' => $branch_list,
                    'scope_id' => $scope_id,
                    'scope_list' => $scope_list,
                    'qris_type' => $request->get('qris_type', []),

                    'merchant_id' => $merchant_id,
                    'branch_id' => $branch_id,

                    'old' => $old
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($scope_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function addFromManageQRIS(Request $request, $id, $merchant_branch_id){
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $merchant_id = $id;
            $requested_branch = $request->get('merchant_branch_id') ? : [$merchant_branch_id];

            $scope_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_PARTNER_LOGIN_GET, [
                'query' => [
                    'merchant_id' => $merchant_id,
                    'branch_id' => $requested_branch,
                    'qris_type' => $request->get('qris_type')
                ]
            ]);

            if ($scope_response->is_ok){
                if ($scope_response->result){
                    $branch_list = $scope_response->result->branch_list;
                    $branch_id = collect($branch_list)->pluck('id');

                    $scope_list = $scope_response->result->selected_branch_list;
                    $scope_id = collect($scope_list)->pluck('edc_id')->toArray();
                }else{
                    $branch_list = [];
                    $branch_id = 0;

                    $scope_list = [];
                    $scope_id = [];
                }

                $old = [];
                if ($request->has('merchant_branch_id')){
                    $merchant_branch_id = $request->get('merchant_branch_id');
                    foreach ($merchant_branch_id as $item){
                        if ($request->has($item)){
                            $old['merchant_branch'][] = [
                                'id' => $item,
                                'name' => $request->get($item)
                            ];
                        }
                    }
                }

                return view("yukk_co.partner_login.add", [
                    'branch_list' => $branch_list,
                    'scope_id' => $scope_id,
                    'scope_list' => $scope_list,

                    'merchant_id' => $id,
                    'old_branch_id' => $merchant_branch_id,
                    'branch_id' => $branch_id,

                    'merchant' => $scope_response->result->merchant,

                    'old' => $old,
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($scope_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request)
    {
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_STORE, [
                'form_params' => [
                    'username' => $request->get('username'),
                    'password' => $request->get('password'),
                    'name' => $request->get('name'),
                    'phone' => $request->get('phone'),
                    'email' => $request->get('email'),
                    'edcs' => $request->get('edcs'),
                ]
            ]);

            if ($response->is_ok){
                $result = $response->result;

                H::flashSuccess($response->status_message, true);
                return redirect(route('yukk_co.partner_login.detail', $result->id));
            }else{
                H::flashFailed($response->status_message, true);
                return redirect(route('yukk_co.partner_login.add'));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function reset(Request $request, $partner_login_id){
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $password = $request->get('password');
            $confirmation_password = $request->get('confirmation_password');

            if ($password !== $confirmation_password){
                H::flashFailed('Password and Confirmation Password didnt match!', true);
                return back();
            }else{
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_RESET_PASSWORD, [
                    "form_params" => [
                        "id" => $partner_login_id,
                        "password" => $password,
                    ],
                ]);

                if ($response->is_ok){
                    H::flashSuccess('Change Password Success', true);
                    return back();
                }else {
                    H::flashFailed(trans($response->status_message), true);
                    return back();
                }
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
    
    public function updateScope(Request $request, $partner_login_id){
        $access_control = "YUKK_MERCHANT.ACCOUNT_LOGIN.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_ACCOUNT_LOGIN_UPDATE_SCOPE, [
                'form_params' => [
                    'id' => $partner_login_id,
                    'merchant_id' => $request->get('id_merchant'),
                    'old_merchant_id' => $request->get('old_merchant_id'),
                    'edcs' => $request->get('edcs'),
                    'branch_id' => $request->get('edcs'),

                    'username' => $request->get('username'),
                    'old_username' => $request->get('old_username'),
                    'name' => $request->get('name'),
                    'phone' => $request->get('phone'),
                    'email' => $request->get('email'),
                    'active' => $request->get('status'),
                ]
            ]);

            if ($response->is_ok){
                H::flashSuccess($response->status_message, true);
                return redirect(route('yukk_co.partner_login.detail', $partner_login_id));
            }else{
                H::flashFailed($response->status_message, true);
                return redirect(route('yukk_co.partner_login.edit', $partner_login_id));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
