<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        $access_controls = ["MASTER_DATA.COMPANY.UPDATE","MASTER_DATA.COMPANY.VIEW"];
        if (!AccessControlHelper::checkCurrentAccessControl($access_controls, "OR")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => json_encode($access_controls),
            ]));
        }

        $query_params = [
            "field" => $request->get('field'),
            "search" => $request->get('search'),
            "page" => $request->get('page'),
            "per_page" => $request->get('per_page'),
            "risk_level" => $request->get('risk_level'),
        ];

        $companies_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_GET_LIST, [
            "query" => $query_params,
        ]);

        if ($companies_response->is_ok) {
            $result = $companies_response->result;
            $company_list = $result->data;

            $from = $result->from;
            $to = $result->to;
            $total = $result->total;
            $current_page = $result->current_page;
            $last_page = $result->last_page;
            $access_control = json_decode(S::getUserRole()->role->access_control);

            return view("yukk_co.companies.list", [
                "companies" => $company_list,
                "from" => $from,
                "to" => $to,
                "total" => $total,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "field" => $query_params['field'],
                "search" => $query_params['search'],
                "risk_level" => $query_params['risk_level'],
                "per_page" => $query_params['per_page'],
                "access_control" => $access_control
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($companies_response);
        }
    }

    public function show($id)
    {
        $access_control = ["MASTER_DATA.COMPANY.UPDATE", "MASTER_DATA.COMPANY.VIEW"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $companies_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_ITEM, [
                "query" => [
                    'company_id' => $id,
                ],
            ]);
            $types = json_decode($companies_response->result->company->type);

            if ($companies_response->is_ok) {
                return view("yukk_co.companies.show", [
                    'company_detail' => $companies_response->result->company,
                    'company_contract_list' => $companies_response->result->contract_list,
                    'types' => $types
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($companies_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $company_id)
    {
        $access_control = "MASTER_DATA.COMPANY.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $companies_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_ITEM, [
                "query" => [
                    'company_id' => $company_id,
                ],
            ]);
            $types = json_decode($companies_response->result->company->type);

            if ($companies_response->is_ok) {
                return view("yukk_co.companies.edit", [
                    'company_detail' => $companies_response->result->company,
                    'company_contract_list' => $companies_response->result->contract_list,
                    'types' => $types
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($companies_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $company_id)
    {
        $access_control = "MASTER_DATA.COMPANY.UPDATE";
        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        if(! $request->get('type')){
            H::flashFailed('Type Must Be Filled!', true);
            return redirect(route('yukk_co.companies.edit', $company_id));
        }

        $company_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_COMPANY_COMPANY_STORE_NAME, [
            "query" =>  [
                'id' => $company_id,
                'name' => $request->get('company_name'),
                'description' => $request->get('description'),
                'type' => $request->get('type'),
                'risk_level' => $request->get('risk_level'),
                'status' => $request->get('status'),
                'button' => $request->get('button'),
                'updated_by' => S::getUser()->username
            ]
        ]);
        
        if ($company_response->is_ok) {
            H::flashSuccess("Edit Success!", true);
            return redirect(route('yukk_co.companies.show', $company_id));
        } else {
            H::flashFailed($company_response->status_message, true);
            return redirect(route('yukk_co.companies.edit', $company_id));
        }
    }

    public function add(Request $request)
    {
        $access_control = "MASTER_DATA.COMPANY.UPDATE";
        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        if($request->get('button') == 'SUBMIT' && (! $request->has('company_contract') || $request->get('type') == '')){
            H::flashFailed('Company Contract or Type Must Be Filled!', true);
            return redirect(route('yukk_co.companies.list'));
        }

        if ($request->filled(['company_contract', 'contract_name', 'contract_description'])) {
            $validationRules = [
                'contract_name' => 'required',
                'contract_description' => 'required',
                'company_contract' => 'required',
            ];

            if ($request->get('contract_type') == 'FILE') {
                $validationRules['company_contract'] .= '|max:2048';
            }

            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return redirect()
                    ->route('yukk_co.companies.list')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }
        }

        $contract = null;
        if($request->has('contract_type')){
            if($request->get('contract_type') == 'FILE'){
                $contract = $request->file('company_contract');
            }elseif($request->get('contract_type') == 'LINK'){
                $contract = $request->get('company_contract');
            }
        }

        $response = ApiHelper::requestGeneral('POST', ApiHelper::END_POINT_COMPANY_ADD_YUKK_CO, [
            "multipart" => [
                [
                    "name" => "name",
                    "contents" => $request->name,
                ],
                [
                    "name" => "description",
                    "contents" => $request->get('description')
                ],
                [
                    "name" => "type",
                    "contents" => $request->get('type') == null ? null : json_encode($request->get('type')),
                ],
                [
                    "name" => "risk_level",
                    "contents" => $request->get('risk_level'),
                ],
                [
                    "name" => "updated_by",
                    "contents" => S::getUser()->username,
                ],
                [
                    "name" => 'contract_name',
                    "contents" => $request->get('contract_name')
                ],
                [
                    "name" => 'contract_description',
                    "contents" => $request->get('contract_description')
                ],
                [
                    "name" => "company_contract",
                    "contents" => $request->get('contract_type') == 'FILE' ? $contract->getContent() : $contract,
                    "filename" => $request->get('contract_type') == 'FILE' ? $contract->getClientOriginalName() : '',
                ],
                [
                    "name" => "contract_type",
                    "contents" => $request->get('contract_type'),
                ],
                [
                    "name" => "button",
                    "contents" => $request->get('button'),
                ]
            ]
        ]);

        if ($response->is_ok) {
            H::flashSuccess('Success', true);
            return redirect(route('yukk_co.companies.show', $response->result->id));
        } else {
            H::flashFailed($response->status_message, true);
            return redirect(route('yukk_co.companies.list'));
        }
    }

    public function deleteCompany(Request $request, $company_id)
    {
        $access_control = "MASTER_DATA.COMPANY.UPDATE";
        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_COMPANY_DESTROY_YUKK_CO, [
            "query" => [
                'company_id' => $company_id,
            ],
        ]);

        if ($response->is_ok) {
            H::flashSuccess('Success Delete', true);
            return redirect(route('yukk_co.companies.list'));
        } else {
            H::flashFailed($response->status_message, true);
            return redirect(route('yukk_co.companies.list'));
        }
    }

    public function listJson(Request $request)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_GET_LIST, [
            'query' => [
                'field' => 'name',
                'search' => $request->get('search'),
                'flag' => $request->get('flag'),
            ]
        ]);

        if ($response->is_ok) {
            return response()->json([
                'result' => $response->result->data,
                'more' => $response->result->next_page_url != null ? true : false,
                'page' => $response->result->current_page ,
            ]);
        } else {
            return response()->json([], 400);
        }
    }
}
