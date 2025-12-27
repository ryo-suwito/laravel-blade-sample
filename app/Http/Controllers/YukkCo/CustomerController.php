<?php

namespace App\Http\Controllers\YukkCo;

use App\CompanyContract;
use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Actions\MerchantAcquisition\InternalBlackList;
use App\Services\APIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\Facades\DataTables;
use GuzzleHttp\Client;

class CustomerController extends BaseController
{
    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $name = $request->get("name", null);

        $query_params = [
            "page" => $page,
        ];

        if ($name) {
            $query_params["name"] = $name;
        }

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_GET_LIST, [
            "query" => $query_params,
        ]);

        if ($response->is_ok) {
            $data = $response->result->data;

            $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
                "query" => [],
            ])->result;

            $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
                "query" => [],
            ])->result;
            $access_control = json_decode(S::getUserRole()->role->access_control);

            return view("yukk_co.customers.list", [
                "data" => $data,
                "banks" => $banks,
                "cities" => $cities,
                "name" => $name,
                "access_control" => $access_control
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function create()
    {
        // obtain is_whitelist parameter from request
        $is_whitelist = request()->get('is_whitelist', false);
        $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
            "query" => [],
        ]);

        if ($banks->is_ok) {
            $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
                "query" => [],
            ]);

            return view("yukk_co.customers.add", [
                "banks" => $banks->result,
                "cities" => $cities->result,
                "is_whitelist" => $is_whitelist,
                "kyc" => null
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($banks);
        }
    }

    public function show($id)
    {
        $access_control = ["MASTER_DATA.BENEFICIARY.VIEW","MASTER_DATA.BENEFICIARY.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_ITEM, [
                "query" => [
                    'customer_id' => $id
                ],
            ]);
            if ($response->is_ok) {
                $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
                    "query" => [],
                ])->result;

                $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
                    "query" => [],
                ])->result;

                $is_whitelist = isset($response->result->is_whitelist) ? $response->result->is_whitelist : false;
                $kyc_response = null;
                if(!$is_whitelist){
                    $kyc_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_KYM_BENEFICIARY_FIND_YUKK_CO . $id, []);
                }

                if($kyc_response && isset($kyc_response->result) && $kyc_response->result != null){
                    $kyc = [
                        "verihubs_status"=> $kyc_response->result->verihubs_status,
                        "verihubs_reason"=> $kyc_response->result->verihubs_reason,
                        "dttot_status"=> $kyc_response->result->dttot_status,
                        "dttot_reason"=>  $kyc_response->result->dttot_reason
                    ];
                } else {
                    $kyc = [
                        "verihubs_status"=> null,
                        "verihubs_reason"=> null,
                        "dttot_status"=> null,
                        "dttot_reason"=> null
                    ];
                }

                $kyc = (object) $kyc;
                return view("yukk_co.customers.show", [
                    'item' => $response->result,
                    "banks" => $banks,
                    "cities" => $cities,
                    "kyc" => $kyc,
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

    public function edit($id)
    {
        $flag = session()->get('flag');
        $status_message = session()->get('status_message');
        $access_control = "MASTER_DATA.BENEFICIARY.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_ITEM, [
                "query" => [
                    'customer_id' => $id
                ],
            ]);
            if ($response->is_ok) {
                $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
                    "query" => [],
                ])->result;

                $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
                    "query" => [],
                ])->result;

                return view("yukk_co.customers.edit", [
                    'item' => $response->result,
                    "banks" => $banks,
                    "cities" => $cities,
                    "flag" => $flag,
                    "status_message" => $status_message,
                    "is_whitelist" => isset($response->result->is_whitelist) ? $response->result->is_whitelist : false
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

    public function update(Request $request){
        $responseDefault = [
            'status'=> 'failed',
            'message'=> 'Failed to store customer data',
            'redirect'=> null
        ];

        $skip_non_bank = request()->get('skip_non_bank', false);
        $is_whitelist = request()->get('is_whitelist', false);

        if($skip_non_bank){
            $rules = [
                'bank_id' => 'required|integer',
                'account_number' => 'required|string',
                'account_name' => 'required|string',
                'branch_name' => 'required|string',
                'disbursement_fee' => 'required|numeric|min:0|max:1000000',
                'auto_disbursement_interval' => [
                    "required",
                    Rule::in(['DAILY', 'WEEKLY', 'ON_HOLD','PAYOUT_BY_REQUEST', 'PAYOUT_BY_REQUEST_PARTNER']),
                ]
            ];
        }else{
            $rules = [
                'name' => 'required|string',
                'created_by' => 'nullable',
                'updated_by' => 'nullable',
                'status' => 'nullable',
                'description' => 'nullable',
            ];
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $responseDefault['message'] = $validator->errors()->first();
            $responseDefault['errors'] = $validator->errors();
            return response()->json($responseDefault, 422);
        }

        if ($validator->errors()->messages() == null) {
            $multiparts = $this->prepareMultipartData($request, $skip_non_bank);

            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_CUSTOMER_UPDATE, [
                "multipart" => $multiparts
            ]);

            if($response->status_code == 6000){
                return response()->json('Success', 200);
            }else{
                return response()->json($response->status_message, $response->status_code);
            }
        } else {
            $responseDefault['message'] = $validator->errors()->first();
            return response()->json($responseDefault, 422);
        }
    }

    public function store(Request $request)
    {
        $responseDefault = [
            'status'=> 'failed',
            'message'=> 'Failed to store customer data',
            'redirect'=> null
        ];

        $rules = [
            'name' => 'required|string',
            'bank_id' => 'required|integer',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'branch_name' => 'required|string',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'status' => 'nullable',
            'description' => 'nullable',
            'disbursement_fee' => 'required|numeric|min:0|max:1000000',
            'auto_disbursement_interval' => [
                "required",
                Rule::in(['DAILY', 'WEEKLY', 'ON_HOLD','PAYOUT_BY_REQUEST', 'PAYOUT_BY_REQUEST_PARTNER']),
            ]
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $responseDefault['message'] = $validator->errors()->first();
            $responseDefault['errors'] = $validator->errors();
            return response()->json($responseDefault, 422);
        }
        
        if ($validator->errors()->messages() == null) {
            $multiparts = $this->prepareMultipartData($request, $skip_non_bank = false);

            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_CUSTOMER_STORE, [
                "multipart" => $multiparts
            ]);
            
            if($response->status_code == 6000){
                return response()->json($response, 200);
            }else{
                return response()->json($response->status_message, $response->status_code);
            }

        } else {
            $responseDefault['message'] = $validator->errors()->first();
            return response()->json($responseDefault, 422);
        }
    }

    public function updateBankOnly(Request $request){
        $request->merge(['skip_non_bank' => true]);
        return $this->update($request);
    }

    public function storeWhiteList(Request $request)
    {
        $request->merge(['is_whitelist' => true]);
        return $this->store($request);
    }  

    // edit whitelist, add parameter is_whitelist = true to request parameter and call original edit function
    public function editWhiteList(Request $request, $id)
    {
        $request->merge(['is_whitelist' => true]);
        // redirect to edit page with is_whitelist = true

    }

    // create whitelist, add parameter is_whitelist = true to request parameter and call original create function
    public function createWhiteList(Request $request)
    {
        $request->merge(['is_whitelist' => true]);
        return $this->create();
    }


    public function prepareMultipartData($data, $skip_non_bank)
    {
        $multiparts = [];
    
        $fields = [
            'id' => $data->id,
            'name' => $data->name,
            'address' => $data->address,
            'email' => $data->email,
            'bank_id' => $data->bank_id,
            'bank_type' => $data->bank_type,
            'account_number' => $data->account_number,
            'account_name' => $data->account_name,
            'branch_name' => $data->branch_name,
            'is_whitelist' => $data->is_whitelist,
            'updated_by' => S::getUser()->id,
            'status' => $data->status ?? 1,
            'skip_non_bank' => $skip_non_bank,
            'disbursement_fee' => $data->disbursement_fee,
            'auto_disbursement_interval' => $data->auto_disbursement_interval
        ];
    
        foreach ($fields as $key => $value) {
            if ($value !== null) {
                $multiparts[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        }
    
        return $multiparts;
    }

    public function data(Request $request)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_GET_LIST, [
            "query" => [
                'per_page' => ($request->length) ? $request->length : 10,
                "keyword" => $request->search['value'],
                "active" => isset($request->status) ? $request->status : null,
                "is_whitelist" => isset($request->is_whitelist) ? $request->is_whitelist : null,
                "auto_disbursement_interval" => isset($request->interval) ? $request->interval : null,
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
        if (in_array('MASTER_DATA.BENEFICIARY.UPDATE', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.customers.edit", $item->id) . '"
            class="dropdown-item"><i class="icon-search4"></i>
            Edit
            </a>';
        }
        if (in_array("MASTER_DATA.BENEFICIARY.VIEW", $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.customers.detail", $item->id) . '"
            class="dropdown-item"><i class="icon-zoomin3"></i>
            Detail
        </a>';
        }
        return $features;
    }

    public function getImage()
    {
        try{
            $url = request()->get('url');
            $url = urldecode($url);
            $client = new Client();
            $response = $client->get($url);
            $image = $response->getBody()->getContents();
        } catch (\Throwable $th) {
            return response(null, 200);
        }
        return response($image, 200)->header('Content-Type', 'image/jpeg');
    }   

    public function bulkSearchForm(Request $request) {
        $access_control = "MASTER_DATA.BENEFICIARY.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        return view("yukk_co.customers.bulk_search");
    }

    public function bulkSearchPost(Request $request) {
        $access_control = "MASTER_DATA.BENEFICIARY.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        if (! $request->hasFile("customers_file")) {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $customers_file = $request->file("customers_file");
        if ($customers_file->getClientOriginalExtension() != "xlsx") {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $allowed_mime_type = [
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];
        if (! in_array($customers_file->getMimeType(), $allowed_mime_type)) {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $customers_spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($customers_file->getRealPath());
        $customers_sheet = $customers_spreadsheet->getActiveSheet();

        $customers = collect([]);
        for ($row = 2; $row <= $customers_sheet->getHighestRow(); $row++) {
            $customers[$customers_sheet->getCellByColumnAndRow(1, $row)->getValue()] = collect([]);
        }

        $customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACQUISITION_CUSTOMER_SEARCH_BULK_BY_NAME, [
            "json" => [
                "customer_names" => $customers->keys(),
            ],
        ]);

        if (! $customer_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($customer_response);
        }

        $customer_grouped = @collect($customer_response->result)->groupBy("name");

        if (! $customer_grouped) {
            return abort(400, "Customer Response Problem");
        }

        for ($row = 2; $row <= $customers_sheet->getHighestRow(); $row++) {
            $customer_name = $customers_sheet->getCellByColumnAndRow(1, $row)->getValue();
            if (isset($customer_grouped[$customer_name])) {
                $customers_sheet->getCellByColumnAndRow(2, $row)->setValue($customer_grouped[$customer_name]->pluck("id")->implode(", "));
            }
        }

        // redirect output to client browser
        header('Content-Disposition: attachment;filename="' . $customers_file->getClientOriginalName() . '"');
        header('Cache-Control: max-age=0');

        //app('debugbar')->disable();
        $writer = new Xlsx($customers_spreadsheet);
        $writer->save('php://output');
    }
}
