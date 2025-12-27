<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MerchantBranchController extends BaseController
{
    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $branch = $request->get("branch", null);
        $partner = $request->get("partner", null);
        $type = $request->get("type", null);
        $per_page = $request->get("per_page", 10);

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
            "partner" => $partner,
            "type" => $type,
        ];
        if ($branch) {
            $query_params["search"] = $branch;
        }

        $response = ApiHelper::requestGeneral('GET', ApiHelper::END_POINT_MERCHANT_BRANCH_GET_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($response->is_ok) {
            $result = $response->result;
            $access_control = json_decode(S::getUserRole()->role->access_control);

            $merchant_branch_list = $result->merchant_branch_list->data;
            $partner_list = $result->partner_list;

            $from = $result->merchant_branch_list->from;
            $to = $result->merchant_branch_list->to;
            $total = $result->merchant_branch_list->total;

            $current_page = $result->merchant_branch_list->current_page;
            $last_page = $result->merchant_branch_list->last_page;

            return view('yukk_co.merchant_branch.list', [
                "merchant_branches" => $merchant_branch_list,
                "partners" => $partner_list,

                "from" => $from,
                "to" => $to,
                "total" => $total,

                "current_page" => $current_page,
                "last_page" => $last_page,

                "branch" => $branch,
                "access_control" => $access_control,
                "partner_id" => $query_params['partner'],
                "type" => $query_params['type'],

                "per_page" => $per_page
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function show($id)
    {
        $access_control = "MASTER_DATA.MERCHANT_BRANCH.VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO, [
                "query" => [
                    'id' => $id,
                ],
            ]);

            $result = $response->result;
            $start_date = date('d-M-Y', strtotime($result->merchant_branch->start_date));
            $end_date = date('d-M-Y', strtotime($result->merchant_branch->end_date));

            return view('yukk_co.merchant_branch.show',[
                'merchant_branch' => $result->merchant_branch,
                'partner_has_merchant_branch' => $result->partner_has_merchant_branch,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $merchant_branch_id){
        $access_control = "MASTER_DATA.MERCHANT_BRANCH.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO, [
                "query" => [
                    'id' => $merchant_branch_id,
                ],
            ]);

            $owners = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
                "query" => [
                    "fields" => "id,name",
                ],
            ]);

            $province = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PROVINCE_LIST_YUKK_CO, []);            

            $result = $response->result;

            return view('yukk_co.merchant_branch.edit',[
                'merchant_branch' => $result->merchant_branch,
                'partner_has_merchant_branch' => $result->partner_has_merchant_branch,
                'start_date' => date('d-M-Y', strtotime($result->merchant_branch->start_date)),
                'end_date' => date('d-M-Y', strtotime($result->merchant_branch->end_date)),
                'owners' => $owners->result,
                'province' => $province->result,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $merchant_branch_id)
    {
        $validator = Validator::make( $request->all() , [
            'merchant_id' => 'required',
            'owner_id' => 'required',
            'name' => 'required',
            'type' => 'required|in:ONLINE,OFFLINE,BOTH',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_terminal' => 'required',
            'qr_type' => 'required|in:b,s,d',
            'longitude' => 'required',
            'latitude' => 'required',
            'province' => 'required',
            'city' => 'required',
            'region' => 'required',
            'postal_code' => 'required',
            'address' => 'required'
        ]);

        if($validator->errors()->messages() == null){
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_UPDATE_YUKK_CO, [
                "form_params" => [
                    'id' => $merchant_branch_id,
                    'merchant_id' => $request->get('merchant_id'),
                    'owner_id' => $request->get('owner_id'),
                    'partner_id' => $request->get('partner_id'),
                    'name' => $request->get('name'),
                    'type' => $request->get('type'),
                    'start_date' => $request->get('start_time'),
                    'end_date' => $request->get('end_time'),
                    'total_terminal' => $request->get('total_terminal'),
                    'qr_type' => $request->get('qr_type'),
                    'longitude' => $request->get('longitude'),
                    'latitude' => $request->get('latitude'),
                    'province' => $request->get('province'),
                    'city' => $request->get('city'),
                    'region' => $request->get('region'),
                    'postal_code' => $request->get('postal_code'),
                    'address' => $request->get('address'),
                    'status' => $request->get('status'),
                    'updated_by' => S::getUser()->id,
                ],
            ]);

            if ($response->is_ok){
                H::flashSuccess('Data changes are successfully saved and are in the process of being reviewed first', true);
                return redirect(route('yukk_co.merchant_branch.show', $response->result->id));
            }else {
                H::flashFailed($response->status_message, true);
                return redirect(route('yukk_co.merchant_branch.edit', $merchant_branch_id));
            }
        }else {
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('yukk_co.merchant_branch.edit', $merchant_branch_id));
        }
    }

    public function add(Request $request)
    {
        $owners = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
            "query" => [
                "fields" => "id,name",
            ],
        ]);

        return view('yukk_co.merchant_branch.add', [
            "owners" => $owners->result,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make( $request->all() , [
            'merchant_id' => 'required',
            'owner_id' => 'required',
            'partner_id' => 'required',
            'name' => ['required', 'max:255'],
            'type' => 'required|in:ONLINE,OFFLINE,BOTH',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_terminal' => 'required',
            'qr_type' => 'required|in:b,s,d',
            'longitude' => 'required',
            'latitude' => 'required',
            'province' => 'required',
            'city' => 'required',
            'region' => 'required',
            'postal_code' => 'required',
            'address' => 'required'
        ]);

        if($validator->errors()->messages() == null){
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_STORE_YUKK_CO, [
                "form_params" => [
                    'merchant_id' => $request->get('merchant_id'),
                    'owner_id' => $request->get('owner_id'),
                    'partner_id' => $request->get('partner_id'),
                    'name' => $request->get('name'),
                    'type' => $request->get('type'),
                    'start_date' => $request->get('start_time'),
                    'end_date' => $request->get('end_time'),
                    'total_terminal' => $request->get('total_terminal'),
                    'qr_type' => $request->get('qr_type'),
                    'longitude' => $request->get('longitude'),
                    'latitude' => $request->get('latitude'),
                    'province' => $request->get('province'),
                    'city' => $request->get('city'),
                    'region' => $request->get('region'),
                    'postal_code' => $request->get('postal_code'),
                    'address' => $request->get('address'),
                    'updated_by' => S::getUser()->id,
                ],
            ]);

            if ($response->is_ok){
                H::flashSuccess('Success', true);
                return redirect(route('yukk_co.merchant_branch.show', $response->result->id));
            }else {
                H::flashFailed($response->status_message, true);
                return redirect(route('yukk_co.merchant_branch.add'))->withInput();
            }
        }else {
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('yukk_co.merchant_branch.add'))->withInput();
        }
    }

    public function bulkCreate()
    {
        return view('yukk_co.merchant_branch.bulk_add');
    }

    public function bulkPreview(Request $request)
    {
        $merchant_branch_file = $request->file('merchant_branch_list');
        $filter_status = $request->get('filter_status', '');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($merchant_branch_file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray();
        $header = null;

        $merchant_branch_list = [];
        $errors = [];

        $validator_merchant_branch = [
            'name' => 'required | string',
            'merchant_id' => 'required',
            'owner_id' => 'required',
            'partner_id' => 'required',
            'merchant_type' => 'required',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'longitude' => 'required|numeric|min:-180|max:180',
            'latitude' => 'required|numeric|min:-90|max:90',
            'address' => 'required',
            'qr_type' => 'required|in:BOTH,DYNAMIC,STATIC',
            'province_name' => 'required',
            'city_name' => 'required',
            'region_name' => 'required',
            'postal_code' => 'required|string|min:5|max:5',
        ];

        $mandatory_headers = [
            'name',
            'merchant_id',
            'customer_id',
            'owner_id',
            'partner_id',
            'merchant_type',
            'start_date',
            'end_date',
            'longitude',
            'latitude',
            'address',
            'qr_type',
            'province_name',
            'city_name',
            'region_name',
            'postal_code'
        ];

        foreach ($data as $row) {
            if (! $header){
                $header = $row;
                // Check for headers that SHOULD NOT be there.
                $unexpected_headers = array_diff($row, $mandatory_headers);
                if (!empty($unexpected_headers)) {
                    H::flashFailed('Error Header(s): ' . implode(', ', $unexpected_headers), true); // Show all unexpected headers
                    return back();
                }
                // Check for headers that SHOULD BE there.
                $missing_headers = array_diff($mandatory_headers, $row);
                if (!empty($missing_headers)) {
                    H::flashFailed('Error Mandatory Header(s): ' . implode(', ', $missing_headers), true); // Show all missing headers
                    return back();
                }
            } else {
                $merchant_branch = array_combine($header, $row);
                if (!$merchant_branch['name']) {
                    continue;
                }
                try {
                    if (isset($merchant_branch['start_date'])) {
                        $merchant_branch['start_date'] = Carbon::createFromFormat("d/M/Y", $merchant_branch['start_date'])->format("Y-m-d");
                    }
                    if (isset($merchant_branch['end_date'])) {
                        $merchant_branch['end_date'] = Carbon::createFromFormat("d/M/Y", $merchant_branch['end_date'])->format("Y-m-d");
                    }
                } catch (\Exception $e) {
                    // Log the exception or handle it appropriately
                    H::flashFailed('Error parsing date (' . $merchant_branch['start_date'] . ' or ' . $merchant_branch['end_date'] . '): ' . $e->getMessage(), true);
                    return back();
                }

                if (isset($merchant_branch['postal_code'])) {
                    $merchant_branch['postal_code'] = (string)$merchant_branch['postal_code'];
                }
                $merchant_branch_list[] = $merchant_branch;
            }
        }

        foreach ($merchant_branch_list as $index => $merchant_branch){
            $validator = Validator::make($merchant_branch, $validator_merchant_branch);

            if ($validator->fails()){
                $errors[$index] = $validator->errors()->getMessages();
            }
        }

        $request_body = $merchant_branch_list;

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_GET_BULK_LIST_YUKK_CO, [
            "json" => $request_body,
        ]);

        if ($response->is_ok){
            $preview_list = [];
            $total_data_count = 0;
            $error_count = 0;
            $valid_row_count = 0;
            $invalid = [];
            $result = $response->result;
            foreach($merchant_branch_list as $index => $merchant_branch){
                $total_data_count++;
                $merchant_branch_object = (object) $merchant_branch;

                $merchant_branch_object->merchant = $result[$index]->merchant;
                $merchant_branch_object->province = $result[$index]->province;
                $merchant_branch_object->city = $result[$index]->city;
                $merchant_branch_object->region = $result[$index]->region;
                $merchant_branch_object->beneficiary = $result[$index]->beneficiary;
                $merchant_branch_object->owner = $result[$index]->owner;
                $merchant_branch_object->partner = $result[$index]->partner;

                $merchant_branch_object->errors = isset($errors[$index]) ? $errors[$index] : null;

                $merchant_has_error = false;
                $errorServices = [];

                if ($result[$index]->merchant == null){
                    $errorServices['merchant_id'] = collect(['Merchant Not Found']);
                    $merchant_has_error = true;
                }
                if($result[$index]->partner == null){
                    $errorServices['partner_id'] = collect(['Partner Not Found']);
                    $merchant_has_error = true;
                }
                if ($result[$index]->province == null){
                    $errorServices['province_name'] = collect(['Province Not Found']);
                    $merchant_has_error = true;
                }
                if ($result[$index]->city == null){
                    $errorServices['city_name'] = collect(['City Not Found']);
                    $merchant_has_error = true;
                }
                if ($result[$index]->region == null){
                    $errorServices['region_name'] = collect(['Region Not Found']);
                    $merchant_has_error = true;
                }
                if ($result[$index]->is_duplicate){
                    $errorServices['is_duplicate'] = 'Duplicate Name Found';
                    $merchant_has_error = true;
                }
                if(! $result[$index]->is_valid_qr_type){
                    $errorServices['is_valid_qr_type'] = 'Invalid QR Type';
                    $merchant_has_error = true;
                }
                if (!$result[$index]->is_valid_start_date){
                    $errorServices['invalid_start_date'] = 'Invalid Start Date';
                    $merchant_has_error = true;
                }
                if (!$result[$index]->is_valid_end_date){
                    $errorServices['invalid_end_date'] = 'Invalid End Date';
                    $merchant_has_error = true;
                }
                if (!$result[$index]->is_valid_latitude) {
                    $errorServices['latitude'] ='Invalid Latitude min -90 max 90';
                    $merchant_has_error = true;
                }
                if (!$result[$index]->is_valid_longitude) {
                    $errorServices['longitude'] ='Invalid Longitude min -180 max 180';
                    $merchant_has_error = true;
                }
                if ($result[$index]->is_valid_beneficiary != 'ok'){
                    $merchant_branch_object->is_valid_beneficiary = collect([$result[$index]->is_valid_beneficiary]);
                    $errorServices['is_valid_beneficiary'] = $merchant_branch_object->is_valid_beneficiary;
                    $merchant_has_error = true;
                } else if(!$merchant_branch_object->beneficiary){
                    $merchant_branch_object->is_valid_beneficiary = 'ok';
                }
                if($result[$index]->is_valid_owner != 'ok') {
                    $merchant_branch_object->is_valid_owner = collect([$result[$index]->is_valid_owner]);
                    $errorServices['is_valid_owner'] = $merchant_branch_object->is_valid_owner;
                    $merchant_has_error = true;
                } else if(!$merchant_branch_object->owner){
                    $merchant_branch_object->is_valid_owner = 'ok';
                }
                if ($merchant_branch_object->errors && isset($merchant_branch_object->errors['postal_code'])){
                    $errorServices['postal_code'] = $merchant_branch_object->errors['postal_code'];
                    $merchant_has_error = true;
                }
                if($result[$index]->is_valid_partner != 'ok'){
                    $errorServices['partner_max_assigned'] = collect([$result[$index]->is_valid_partner]);
                    $merchant_has_error = true;
                }
                if($result[$index]->is_valid_merchant_type != 'ok'){
                    $errorServices['merchant_type'] = $result[$index]->is_valid_merchant_type;
                    $merchant_has_error = true;
                }

                // Assign errors to the merchant branch object
                $merchant_branch_object->errorServices = $errorServices;

                $merchant_branch_object->errors = isset($errors[$index]) ? $errors[$index] : null;

                // Count errors and valid rows
                if ($merchant_has_error) {
                    $error_count++;
                    $invalid[] = $merchant_branch_object;
                } else {
                    $valid_row_count++;
                }

                if (! $merchant_has_error) {
                    array_unshift($preview_list, $merchant_branch_object);
                } else {
                    $preview_list[] = $merchant_branch_object;
                }
            }

            // Filter the preview list based on the filter status
            if ($filter_status === 'ok') {
                $preview_list = array_filter($preview_list, function ($item) {
                    return !$item->errors && !$item->errorServices;
                });
            } elseif ($filter_status === 'not_ok') {
                $preview_list = array_filter($preview_list, function ($item) {
                    return $item->errors || $item->errorServices;
                });
            }

            return view('yukk_co.merchant_branch.bulk_detail', [
                'preview_list' => $preview_list,
                'total_data_count' => $total_data_count,
                'error_count' => $error_count,
                'valid_row_count' => $valid_row_count,
                'filter_status' => $filter_status,
                'invalid' => $invalid
            ]);

        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function bulkStore(Request $request)
    {
        $selected_merchant_branch_list = [];
        foreach ($request->get("checkbox", array()) as $index => $on) {
            $selected_merchant_branch_list[] = [
                "name" => $request->get("name")[$index],
                "merchant_id" => $request->get("merchant_id")[$index],
                "beneficiary_id" => $request->get("beneficiary_id")[$index],
                "owner_id" => $request->get("owner_id")[$index],
                "partner_id" => $request->get("partner_id")[$index],
                "start_date" => $request->get("start_date")[$index],
                "end_date" => $request->get("end_date")[$index],
                "longitude" => $request->get("longitude")[$index],
                "latitude" => $request->get("latitude")[$index],
                "address" => $request->get("address")[$index],
                "qr_type" => $request->get("qr_type")[$index],
                "type" => $request->get("merchant_type")[$index],
                "province_name" => $request->get("province_name")[$index],
                "city_name" => $request->get("city_name")[$index],
                "region_name" => $request->get("region_name")[$index],
                "postal_code" => $request->get("postal_code")[$index],
            ];
        }

        $request_body = $selected_merchant_branch_list;

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_STORE_BULK_LIST_YUKK_CO, [
            "json" => $request_body,
        ]);
        
        if ($response->is_ok){
            H::flashSuccess('Success', true);
            return redirect(route('yukk_co.merchant_branch.list'));
        }else {
            H::flashFailed($response->status_message, true);
            return redirect(route('yukk_co.merchant_branch.list'));
        }
    }

    public function inactivate(Request $request) {
        $access_control = "MASTER_DATA.MERCHANT_BRANCH_ACTIVATION.UPDATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $merchant_branch_id = $request->get("merchant_branch_id");
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_INACTIVATE_YUKK_CO, [
            "form_params" => [
                'merchant_branch_id' => $merchant_branch_id,
            ],
        ]);

        if (! $response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
        $result = $response->result;

        S::flashSuccess($response->status_message, true);
        return redirect(route("yukk_co.merchant_branch.show", $result->id));
    }

    public function bulkSearchForm(Request $request) {
        return view("yukk_co.merchant_branch.bulk_search");
    }

    public function bulkSearchPost(Request $request) {
        if (! $request->hasFile("merchant_branch")) {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $merchant_branch_file = $request->file("merchant_branch");
        if ($merchant_branch_file->getClientOriginalExtension() != "xlsx") {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $allowed_mime_type = [
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];
        if (! in_array($merchant_branch_file->getMimeType(), $allowed_mime_type)) {
            H::flashFailed(trans("cms.Please Upload Excel File (xlsx)"), true);
            return back();
        }

        $merchant_branches_spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($merchant_branch_file->getRealPath());
        $merchant_branches_sheet = $merchant_branches_spreadsheet->getActiveSheet();

        $merchant_branches = collect([]);
        for ($row = 2; $row <= $merchant_branches_sheet->getHighestRow(); $row++) {
            $merchant_branches[$merchant_branches_sheet->getCellByColumnAndRow(2, $row)->getValue()] = collect([]);
        }
        $response = ApiHelper::requestGeneral("POST", "merchant-acquisition/merchant_branch/bulk_search", [
            "json" => [
                "merchant_branch_names" => $merchant_branches->keys(),
            ],
        ]);

        if (! $response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }

        $merchant_branch_grouped = @collect($response->result)->groupBy("name");

        if (! $merchant_branch_grouped) {
            return abort(400, "Merchant Branch Response Problem");
        }

        for ($row = 2; $row <= $merchant_branches_sheet->getHighestRow(); $row++) {
            $merchant_branch_name = $merchant_branches_sheet->getCellByColumnAndRow(2, $row)->getValue();
            if (isset($merchant_branch_grouped[$merchant_branch_name])) {
                $merchant_branches_sheet->getCellByColumnAndRow(3, $row)->setValue($merchant_branch_grouped[$merchant_branch_name]->pluck("id")->implode(", "));
            }
        }

        // redirect output to client browser
        header('Content-Disposition: attachment;filename="' . $merchant_branch_file->getClientOriginalName() . '"');
        header('Cache-Control: max-age=0');

        //app('debugbar')->disable();
        $writer = new Xlsx($merchant_branches_spreadsheet);
        $writer->save('php://output');
    }
}
