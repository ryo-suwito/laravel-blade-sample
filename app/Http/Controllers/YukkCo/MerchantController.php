<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MerchantController extends BaseController
{
    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $search = $request->get("search", null);
        $field = $request->get('field');

        $query_params = [
            "page" => $page,
            "search" => $search,
            "field" => $field,
            "per_page" => $request->get('per_page', 10)
        ];

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_GET_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($response->is_ok) {
            $result = $response->result;
            $access_control = json_decode(S::getUserRole()->role->access_control);

            $merchant_list = $result->data;

            $from = $result->from ?? 0;
            $to = $result->to ?? 0;
            $total = $result->total ?? 0;

            $current_page = $result->current_page;
            $last_page = $result->last_page;

            return view("yukk_co.merchant.list", [
                "merchants" => $merchant_list,
                "from" => $from,
                "to" => $to,
                "total" => $total,
                "per_page" => $query_params['per_page'],
                "current_page" => $current_page,
                "last_page" => $last_page,
                "search" => $search,
                "field" => $field,
                "access_control" => $access_control
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function show($id)
    {
        $access_control = "MASTER_DATA.MERCHANT.VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_DETAIL_YUKK_CO, [
                "query" => [
                    'id' => $id,
                ],
            ]);

            if ($response->is_ok){
                return view("yukk_co.merchant.show", [
                    'merchant' => $response->result->merchant,
                    'response' => $response->result
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $merchant_id)
    {
        $access_control = "MASTER_DATA.MERCHANT.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_DETAIL_YUKK_CO, [
                "query" => [
                    'id' => $merchant_id,
                ],
            ]);
            if ($response->is_ok){
                return view("yukk_co.merchant.edit", [
                    'merchant' => $response->result->merchant,
                    'response' => $response->result
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $merchant_id)
    {
        $validator = Validator::make( $request->all() , [
            'image_logo' => 'mimes:jpg,jpeg,png',
            'merchant_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if($validator->errors()->messages() == null){
            $file = $request->file('image_logo');
            if ($file){
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_EDIT_YUKK_CO, [
                    "multipart" => [
                        [
                            "name" => "id",
                            "contents" => $merchant_id,
                        ],
                        [
                            "name" => "category_id",
                            "contents" => $request->get('category_id'),
                        ],
                        [
                            "name" => "mcc",
                            "contents" => $request->mcc,
                        ],
                        [
                            "name" => "merchant_type",
                            "contents" => $request->merchant_type,
                        ],
                        [
                            "name" => "mdr_fee",
                            "contents" => $request->mdr_fee,
                        ],
                        [
                            "name" => "merchant_criteria",
                            "contents" => $request->merchant_criteria,
                        ],
                        [
                            "name" => "status",
                            "contents" => $request->status,
                        ],
                        [
                            "name" => "image_logo",
                            "contents" =>  $file->getContent(),
                            "filename" => $file->getClientOriginalName(),
                        ]
                    ],
                ]);
            }else{
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_EDIT_YUKK_CO, [
                    "query" => [
                        'id' => $merchant_id,
                        'merchant_name' => $request->merchant_name,
                        'description' => $request->description,
                        'category_id' => $request->category_id,
                        'mcc' => $request->mcc,
                        'merchant_type' => $request->merchant_type,
                        'mdr_fee' => $request->mdr_fee,
                        'merchant_criteria' => $request->merchant_criteria,
                        'qr_type' => $request->qr_type,
                        'status' => $request->status
                    ],
                ]);
            }

            if ($response->is_ok){
                H::flashSuccess('Data changes are successfully saved and are in the process of being reviewed first', true);
                return redirect(route('yukk_co.merchant.show', $merchant_id));
            }else {
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        }else {
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('yukk_co.merchant.detail', $merchant_id));
        }
    }

    public function add()
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_ADD_YUKK_CO, []);

        if ($response->is_ok){
            return view("yukk_co.merchant.add", [
                "response" => $response->result,
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make( $request->all() , [
            'merchant_name' => 'required',
            'company_name' => 'required',
            'company_category' => 'required',
            'mcc' => 'required',
            'mdr_fee' => 'required',
            'merchant_criteria' => 'required',
            'image_logo' => 'required|mimes:jpg,jpeg,png'
        ]);

        if($validator->errors()->messages() == null){
            $file = $request->file('image_logo');
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_STORE_YUKK_CO, [
                    "multipart" => [
                        [
                            "name" => "merchant_name",
                            "contents" => $request->merchant_name,
                        ],
                        [
                            "name" => "description",
                            "contents" => $request->description,
                        ],
                        [
                            "name" => "company_name",
                            "contents" => $request->company_name,
                        ],
                        [
                            "name" => "company_category",
                            "contents" => $request->company_category,
                        ],
                        [
                            "name" => "mcc",
                            "contents" => $request->mcc,
                        ],
                        [
                            "name" => "merchant_type",
                            "contents" => $request->merchant_type,
                        ],
                        [
                            "name" => "mdr_fee",
                            "contents" => $request->mdr_fee,
                        ],
                        [
                            "name" => "merchant_criteria",
                            "contents" => $request->merchant_criteria,
                        ],
                        [
                            "name" => "image_logo",
                            "contents" =>  $file->getContent(),
                            "filename" => $file->getClientOriginalName(),
                        ]
                    ],
                ]);

            if ($response->is_ok){
                H::flashSuccess('Success', true);
                return redirect(route('yukk_co.merchant.show', $response->result->id));
            }else {
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        }else {
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('yukk_co.merchant.add'));
        }
    }

    public function destroy(Request $request, $merchant_id)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_DESTROY_YUKK_CO, [
            "query" => [
                'merchant_id' => $merchant_id,
            ],
        ]);

        if ($response->is_ok){
            H::flashSuccess('Success Delete', true);
            return redirect(route('yukk_co.merchants.list'));
        }else{
            H::flashFailed($response->status_message, true);
            return redirect(route('yukk_co.merchants.list'));
        }
    }

    public function listJson(Request $request)
    {
        $company_id = $request->get('company_id');

        $query_params = [
            'company_id' => $company_id,
            "per_page" => 99999999,
            "search" => $request->get('search'),
        ];

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_GET_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($response->is_ok){
            return response()->json($response->result->data);
        }else{
            return response()->json([],400);
        }
    }

    public function data(Request $request)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_GET_LIST_YUKK_CO, [
            "query" => [
                'per_page' => ($request->length) ? $request->length : 10,
                "keyword" => $request->search['value'],
                'page' => ($request->start + $request->length) / $request->length
            ],
        ]);
        $request->merge(['start' => 0]);

        return DataTables::of($response->result->data)
            ->setFilteredRecords($response->result->total)
            ->setTotalRecords($response->result->total)
            ->addColumn('action', function ($item) {
                $features = $this->features($item);
                return '
                <div class="list-icons">
                    <div class="dropdown">
                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">'.$features.'</div>
                    </div>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function features($item)
    {
        $access_control = json_decode(S::getUserRole()->role->access_control);
        $features = '';
        if (in_array('MASTER_DATA.MERCHANT.UPDATE', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.merchant.detail", $item->id) . '"
            class="dropdown-item"><i class="icon-search4"></i>
            Edit
        </a>';
        }
        if (in_array('MASTER_DATA.MERCHANT.VIEW', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.merchant.show", $item->id) . '"
            class="dropdown-item"><i class="icon-zoomin3"></i>
            Detail
        </a>';
        }
        if (in_array('MASTER_DATA.MERCHANT.UPDATE', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.merchant.delete", $item->id) . '"
            class="dropdown-item"><i class="icon-trash"></i>
            Delete
        </a>';
        }
        return $features;
    }
}
