<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Services\APIService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use http\Env\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Actions\MerchantAcquisition\FilterDeletePten;
use App\Actions\MerchantAcquisition\GetDeletePten;
use App\Actions\MerchantAcquisition\DeletePten;
use App\Actions\MerchantAcquisition\GetActivityDeletePten;
use App\Actions\MerchantAcquisition\ImportDeletePten;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeletePtenExport;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;

class MerchantBranchPTENController extends BaseController
{
    public function index(Request $request)
    {
        $status = $request->get("status", null);
        $query_params = [
            "status" => $status,
        ];
        if (isset($request->DataTables_Table_0_length)) {
            $query_params['per_page'] = $request->DataTables_Table_0_length;
        }
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_PTEN_GET_LIST, [
            "query" => $query_params,
        ]);
        if ($response->is_ok) {
            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=MerchantBranchPTEN.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'Merchant Name',
                    'Branch Name',
                    'Company Name',
                    'Beneficiary Name',
                    'Start Contract Date',
                    'End Contract Date',
                    'MID',
                    'MPAN',
                    'NMID',
                    'Status',
                    'Last Updated At',
                ];

                $callback = function () use ($columns, $response) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    foreach ($response->result->data as $merchant_branch) {
                        fputcsv($file, [
                            @$merchant_branch->merchant->name,
                            @$merchant_branch->name,
                            @$merchant_branch->merchant->company->name,
                            @$merchant_branch->customer->name,
                            @\App\Helpers\H::formatDateTime($merchant_branch->start_date),
                            @\App\Helpers\H::formatDateTime($merchant_branch->end_date),
                            @strval($merchant_branch->mid),
                            @strval($merchant_branch->mpan),
                            @$merchant_branch->nmid_pten,
                            @$merchant_branch->status_pten,
                            @\App\Helpers\H::formatDateTime($merchant_branch->updated_at),
                        ]);
                    }
                    fclose($file);
                };
                return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
            }

            $access_control = json_decode(S::getUserRole()->role->access_control);

            return view("yukk_co.merchant_pten.list", [
                "access_control" => $access_control,
                "status" => $status,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function submit(Request $request)
    {
        return view("yukk_co.merchant_pten.add");
    }

    public function show($id)
    {
        $item = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO, [
            "query" => [
                "id" => $id,
            ],
        ]);

        if ($item->is_ok) {
            $companies = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_GET_LIST, [
                "query" => [
                    // "page" => 99999999,
                ],
            ]);
            $beneficiaries = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_GET_LIST, [
                "query" => [],
            ]);

            return view("yukk_co.merchant_pten.show", [
                "companies" => $companies->result->data,
                "beneficiaries" => $beneficiaries->result->data,
                "item" => $item->result->merchant_branch
                // "last_page" => $last_page,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($item);
        }
    }

    public function edit($id)
    {
        $item = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO, [
            "query" => [
                "id" => $id,
            ],
        ]);
        if ($item->is_ok) {
            $enableSubmit = false;

            $item = $item->result->merchant_branch;
            if ($item->status_pten == 'REJECTED' || ($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == true)) {
                $enableSubmit = true;
            }
            return view("yukk_co.merchant_pten.edit", [
                "item" => $item,
                "enableSubmit" => $enableSubmit
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($item);
        }
    }

    public function changeStatusPten(Request $request)
    {
        $data = [
            "id" => $request->merchant_branch_id,
            "status_pten" => $request->status_pten,
            "updated_by" => S::getUser()->id
        ];

        if (isset($request->customer_id)) {
            $data['customer_id'] = $request->customer_id;
        }

        $response = $this->changeStatus($data);

        if ($response->is_ok) {
            H::flashSuccess('Beneficiary is Assigned', true);
            return redirect()->route('yukk_co.merchant.pten.list');
        } else {
            H::flashFailed($response->status_message, true);
            return redirect()->back();
        }
    }

    public function changeStatusPending(Request $request)
    {
        $data = [
            "id" => $request->merchant_branch_id,
            "last_submit_to_pten_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "status_pten" => 'PENDING_TO_PTEN',
            "updated_by" => S::getUser()->id
        ];

        if (isset($request->customer_id)) {
            $data['customer_id'] = $request->customer_id;
        }

        $response = $this->changeStatus($data);

        if ($response->is_ok) {
            H::flashSuccess('Success Submit to PTEN', true);
            return redirect()->route('yukk_co.merchant.pten.list');
        } else {
            H::flashFailed($response->status_message, true);
            return redirect()->back();
        }
    }

    public function changeStatusPendingFromJson(Request $request)
    {
        $data = [
            "id" => $request->merchant_branch_id,
            "last_submit_to_pten_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "status_pten" => 'PENDING_TO_PTEN',
            "updated_by" => S::getUser()->id
        ];

        if (isset($request->customer_id)) {
            $data['customer_id'] = $request->customer_id;
        }

        $response = $this->changeStatus($data);

        if ($response->is_ok) {
            return response()->json($response->result);
        } else {
            return response()->json($response);
        }
    }

    public function changeStatus($data)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_PTEN_UPDATE_STATUS_PENDING, [
            "form_params" => $data,
        ]);

        return $response;
    }

    public function data(Request $request)
    {

        $status = $request->input("status", null);
        $query_params = [
            "keyword" => $request->input('search.value', ''),
            "status" => $status,
            'per_page' => ($request->input('length')) ? $request->input('length') : 10,
            'page' => ($request->input('start') + $request->input('length')) / $request->input('length')
        ];
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_PTEN_GET_LIST, [
            "query" => $query_params,
        ]);
        $request->merge(['start' => 0]);



        return DataTables::of((array) $response->result->data)
            ->setFilteredRecords($response->result->total)
            ->setTotalRecords($response->result->total)
            ->editColumn('merchant.name', function ($item) {
                return $item->merchant ? $item->merchant->name : '';
            })

            ->editColumn('status_pten', function ($item) {
                if ($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == false) {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-danger badge-pill ml-auto">NOT COMPLETED</span></div>';
                } elseif ($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == true) {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-info badge-pill ml-auto">READY</span></span></div>';
                } elseif ($item->status_pten == 'WAITING_FROM_PTEN' || $item->status_pten == 'PENDING_TO_PTEN') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-warning badge-pill ml-auto">PENDING</span></span></div>';
                } elseif ($item->status_pten == 'APPROVED') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-success badge-pill ml-auto">APPROVED</span></span></div>';
                } elseif ($item->status_pten == 'APPROVED_PROCESSED') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-success badge-pill ml-auto">APPROVED_PROCESSED</span></span></div>';
                } elseif ($item->status_pten == 'REJECTED') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-secondary badge-pill ml-auto">REJECTED</span></span></div>';
                } elseif ($item->status_pten == 'PENDING_DELETE_PTEN') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-warning badge-pill ml-auto">PENDING DELETE</span></span></div>';
                } elseif ($item->status_pten == 'WAITING_DELETE_PTEN') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-info badge-pill ml-auto">WAITING DELETE PTEN</span></span></div>';
                } elseif ($item->status_pten == 'REJECTED_DELETE_PTEN') {
                    return '<div id="status_' . $item->id . '"><span class="badge badge-danger badge-pill ml-auto">REJECTED DELETE</span></span></div>';
                }
            })
            ->addColumn('action', function ($item) {
                $features = $this->features($item);
                if ($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == false) {
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
                } elseif (($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == true) || $item->status_pten == 'REJECTED') {
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
                } elseif (in_array($item->status_pten, ['WAITING_FROM_PTEN', 'PENDING_TO_PTEN', 'APPROVED', 'APPROVED_PROCESSED', 'PENDING_DELETE_PTEN', 'WAITING_DELETE_PTEN', 'REJECTED_DELETE_PTEN'])) {
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
                }
            })
            ->rawColumns(['action',  'status_pten', 'merchant.name'])
            ->make();
    }

    public function features($item)
    {
        $access_control = json_decode(S::getUserRole()->role->access_control);
        $features = '';
        if ($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == false) {
            if (in_array('QRIS_MENU.QRIS_PTEN.VIEW', $access_control)) {
                $features = $features . '<a href="' . route("yukk_co.merchant.pten.show", $item->id) . '"
                class="dropdown-item"><i class="icon-zoomin3"></i>
                Detail
                </a>';
            }
            if (in_array('QRIS_MENU.QRIS_PTEN.UPDATE', $access_control)) {
                $features = $features . '<a href="' . route("yukk_co.merchant.pten.edit", $item->id) . '"
                class="dropdown-item"><i class="icon-pencil7"></i>
                Edit
                </a>';
            }
        } elseif (($item->status_pten == 'READY_TO_SUBMIT' && $item->data_for_pten_complete == true) || $item->status_pten == 'REJECTED') {
            if (in_array('QRIS_MENU.QRIS_PTEN.VIEW', $access_control)) {
                $features = $features . '<a href="' . route("yukk_co.merchant.pten.show", $item->id) . '"
                    class="dropdown-item"><i class="icon-zoomin3"></i>
                    Detail
                </a>';
            }
            if (in_array('QRIS_MENU.QRIS_PTEN.UPDATE', $access_control)) {
                $features = $features . '<a href="' . route("yukk_co.merchant.pten.edit", $item->id) . '"
                    class="dropdown-item"><i class="icon-pencil7"></i>
                    Edit
                </a>
                <button type="button"  data-id="' . $item->id . '" data-toggle="modal" data-target="#submit-to-pten-modal"
                    class="dropdown-item submit-pten" onclick="submitToPten(\'' . $item->name . '\')"><i class="icon-paperplane"></i>
                    Submit
                </button>';
            }
        } elseif (in_array($item->status_pten, ['WAITING_FROM_PTEN', 'PENDING_TO_PTEN', 'APPROVED', 'APPROVED_PROCESSED', 'PENDING_DELETE_PTEN', 'WAITING_DELETE_PTEN', 'REJECTED_DELETE_PTEN'])) {
            if (in_array('QRIS_MENU.QRIS_PTEN.VIEW', $access_control)) {
                $features = $features . '<a href="' . route("yukk_co.merchant.pten.show", $item->id) . '"
                    class="dropdown-item"><i class="icon-zoomin3"></i>
                    Detail
                </a>';
            }
        }
        return $features;
    }

    public function listDownload(Request $request)
    {
        $page = $request->get("page", 1);
        $branch = $request->get("branch", null);
        $per_page = $request->get("per_page", null);


        $query_params = [
            "status" => [
                'APPROVED',
                'APPROVED_PROCESSED'
            ],
            "per_page" => $per_page,
            "page" => $page,
            "download_qris" => "1",
            "branch" => $branch
        ];

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_PTEN_MERCHANT_BRANCH_GET_LIST, [
            "query" => $query_params,
        ]);

        if ($response->is_ok) {
            $merchant_branch_list = $response->result->data;
            $current_page = $response->result->current_page;
            $last_page = $response->result->last_page;

            return view('yukk_co.merchant_pten.list_download_qris', [
                'merchant_branch_list' => $merchant_branch_list,
                'current_page' => $current_page,
                'last_page' => $last_page,

                'branch' => $branch,
                'per_page' => $per_page
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function listQRIS(Request $request)
    {
        $selected_qris_file = [];
        if(! $request->get("checkbox")){
            H::flashFailed('Please Select At Least 1 QRIS', true);
            return back();
        }

        foreach ($request->get("checkbox") as $index => $on) {
            $selected_qris_file[] = $index;
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DOWNLOAD_QRIS_MERCHANT_BRANCH, [
            "query" => [
                "ids" => $selected_qris_file
            ],
        ]);

        if($response->is_ok){
            // Get from Internet
            $client = new Client();

            $qris_file = [];
            foreach($response->result as $item){
                if($item->qr_static_path){
                    $image = $client->get($item->qr_static_path);
                    if ($image->getStatusCode() == 200) {
                        $qris_file[] = [
                            "file_name" => $item->name,
                            "qris_file" => $item->qr_static_path,
                            "blob" => $image->getBody()->getContents(),
                        ];
                    } else {
                        \Log::error('Failed to Attach QRIS '. $item->name . ' with id '. $item->id);
                        H::flashFailed('Failed to download QRIS because the image was not found. Please contact our customer service.', true);
                        return back();
                    }
                }else{
                    \Log::error('Failed to Attach QRIS ' . $item->name . ' Because QR Static Path Null');
                }
            }

            if(count($qris_file) == 0){
                \Log::info('Failed to download QRIS Because All QR Static Path Null');
                H::flashFailed('Failed to download QRIS because the image was not found. Please contact our customer service.', true);
                return back();
            }

            $zip = new \ZipArchive();
            $fileName = storage_path('app/QRISFile.zip');
            if ($zip->open($fileName, \ZipArchive::OVERWRITE | \ZipArchive::CREATE) == TRUE) {
                $files = $qris_file;
                foreach ($files as $value) {
                    $file_name = $value['file_name'].'.png';
                    $zip->addFromString($file_name, $value['blob']);
                }
                $zip->close();
            }
        }

        return \response()->download($fileName);
    }

    public function list(Request $request)
    {
        return view('yukk_co.merchant_pten.list_bulk_submit');
    }

    public function listData(Request $request)
    {
        $page = ($request->input('start', 0) / $request->input('length', 10)) + 1;
        $perPage = $request->input('length', 10);

        // Retrieve search term
        $search = $request->input('search')['value'] ?? null;

        $query_params = [
            "status" => [
                'READY_TO_SUBMIT',
                'REJECTED',
            ],
            "per_page" => $perPage,
            "page" => $page,
        ];

        // Check if a search term is provided
        if ($search) {
            $query_params["search"] = $search;
        }

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_PTEN_MERCHANT_BRANCH_GET_LIST, [
            "query" => $query_params,
        ]);

        if ($response->is_ok) {
            $data = $response->result;

            return response()->json([
                'data' => $data->data,
                'recordsTotal' => $data->total,
                'recordsFiltered' => $data->total,  // Update based on search results if needed
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to retrieve merchant branches',
                'success' => false,
            ], 500);
        }
    }

    public function listDelete(Request $request, GetDeletePten $deletePten, GetActivityDeletePten $activityDelete)
    {
        $access_control = "QRIS_MENU.QRIS_PTEN.DELETE_QRIS";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $filter = new FilterDeletePten($request);
        $delete_pten = $deletePten->get($filter->values());
        $activity_delete = $activityDelete->get();

        $items = collect($activity_delete)->map(function ($row) {
            return $row['new_row_id'];
        });

        return view('yukk_co.merchant_pten.list_delete_qris', [
            'merchant_branch_list' => $delete_pten,
            'current_page' => $delete_pten->currentPage(),
            'last_page' => $delete_pten->lastPage(),
            'per_page' => $delete_pten->perPage(),
            'search'    => $request->get('search'),
            'filter'    => $request->get('type'),
            'activity_delete' => json_decode($items),
        ]);
    }

    public function listDeletePten(Request $request, DeletePten $deletePten)
    {
        $access_control = "QRIS_MENU.QRIS_PTEN.DELETE_QRIS";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $value = $request->input('ids');
        $reason = $request->input('keterangan');
        $ids = explode(",", $value);
        $delete_pten = $deletePten->update($ids, $reason);

        H::flashSuccess("Data changes are successfully saved and are in the process of being reviewed first", true);
        return redirect()->route('yukk_co.merchant.pten.list');
    }

    public function ImportDeletePten(Request $request, ImportDeletePten $importDelete, GetActivityDeletePten $activityDelete)
    {
        $access_control = "QRIS_MENU.QRIS_PTEN.DELETE_QRIS";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        try {
            $requestItems = $importDelete->importDelete($request->file('import'));
        } catch (\Throwable $e) {
            H::flashFailed($e->getMessage(), true);
            return redirect()->route('yukk_co.merchant.pten.delete.list');
        }

        $activity_delete = $activityDelete->get();
        $items = collect($activity_delete)->map(function ($row) {
            return $row['new_row_id'];
        });
        $id_activity = json_decode($items);

        $resultItems = collect($requestItems)
                    ->map(function($item) use($id_activity) {
                        if(! is_numeric($item['id'])){
                            $item['status_ok'] = 0;
                            $item['message'] = "Invalid format data";
                        }else if($item['duplicate'] == true){
                            $item['status_ok'] = 0;
                            $item['message'] = "Duplicate data";
                        }else if($item['merchant_id'] != '' && $item['mid'] != ''){

                            if(in_array($item['status_pten'], ['APPROVED', 'APPROVED_PROCESSED', 'REJECTED_DELETE_PTEN'])){

                                if(in_array($item['id'], $id_activity) == false){
                                    $item['status_ok'] = 1;
                                    $item['message'] = "";
                                }else{
                                    $item['status_ok'] = 0;
                                    $item['message'] = "A deletion request for this QRIS is already pending";
                                }

                            }else{
                                $item['status_ok'] = 0;
                                $item['message'] = "Qris cannot be deleted. Status should be APPROVED / APPROVED_PROCESSED";
                            }

                        }else{
                            $item['status_ok'] = 0;
                            $item['message'] = "ID is not found";
                        }

                        return $item;
                    })
                    ->sortByDesc('status_ok');

        return view('yukk_co.merchant_pten.import_delete_qris', [
            'merchant_branch_list' => $resultItems,
            'search'    => '',
            'filter'    => '',
            'count_ok'  => 0
        ]);
    }

    public function bulkSubmit(Request $request)
    {
        $selected_merchant_branches_id = [];

        foreach ($request->get("checkbox") as $index => $id) {
            $selected_merchant_branches_id[] = [
                "id" => $id
            ];
        }

        $request_body['ids'] = $selected_merchant_branches_id;
        $request_body['updated_by'] = S::getUser()->id;

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PTEN_MERCHANT_BRANCH_BULK_SUBMIT, [
            "json" => $request_body,
        ]);

        if ($response->is_ok) {
            H::flashSuccess('Success', true);
            return redirect(route('yukk_co.merchant.pten.list'));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function getCompanyJson(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_GET_LIST, [
            "query" => [
                "per_page" => 10,
            ],
        ]);

        return response()->json($data->result->data);
    }

    public function getCompanyJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_COMPANY_COMPANY_GET_LIST, [
            "query" => [
                "search" => $request->search,
                "per_page" => 10,
                "page" => $request->page
            ],
        ]);

        return response([
            'result' => $data->result->data,
            'more' => $data->result->next_page_url != null ? true : false,
            'page' => $data->result->current_page,
        ]);
    }

    public function getMerchantJson(Request $request)
    {
        $merchants = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BY_COMPANY_GET, [
            "query" => [
                "company_id" => $request->get('company_id', null),
                "per_page" => 10
            ],
        ]);

        return response()->json($merchants->result->data);
    }

    public function getMerchantJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BY_COMPANY_GET, [
            "query" => [
                "search" => $request->search,
                "company_id" => $request->company_id,
                "page" => $request->page,
                "per_page" => 10
            ],
        ]);

        return response([
            'result' => $data->result->data,
            'more' => $data->result->next_page_url != null ? true : false,
            'page' => $data->result->current_page,
        ]);
    }

    public function getBranchJson(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_MERCHANT_GET, [
            "query" => [
                "merchant_id" => $request->merchant_id,
            ],
        ]);

        return response()->json($data->result);
    }

    public function getBranchJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_BY_MERCHANT_GET, [
            "query" => [
                "search" => $request->search,
                "merchant_id" => $request->merchant_id,
                "merchant_branch_id" => $request->merchant_branch_id,
                "page" => $request->get('page', 1),
                "per_page" => $request->get('per_page', 10)
            ],
        ]);
        $response = array();

        foreach ($data->result->results as $item) {
            $response[] = array(
                "id" => $item->id,
                "text" => $item->name
            );
        }
        $res = [
            'results' => $response,
            'pagination' => [
                'more' => $data->result->pagination->more
            ]
        ];
        return response()->json($res);
    }

    public function getBranchByIdJson(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_ITEM_YUKK_CO, [
            "query" => [
                "id" => $request->merchant_branch_id,
            ],
        ]);

        return response()->json($data->result);
    }

    public function getCustomerJson(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_GET_LIST, [
            "query" => [
                "per_page" => 10,
            ],
        ]);

        return response()->json($data->result->data);
    }

    public function getCustomerJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CUSTOMER_GET_LIST, [
            "query" => [
                "search" => $request->search,
                "per_page" => 10,
                "page" => $request->get('page'),
            ],
        ]);
        $response = array();

        foreach ($data->result->data as $item) {
            $response[] = array(
                "id" => $item->id,
                "text" => $item->name
            );
        }

        return response()->json($response);
    }

    public function getPartnerJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_LIST_YUKK_CO, [
            "query" => [
                "search" => $request->search,
                "page" => $request->get('page'),
            ],
        ]);
        $response = array();

        foreach ($data->result->data as $item) {
            $response[] = array(
                "id" => $item->id,
                "text" => $item->name,
                "is_snap_enable" => $item->is_snap_enabled
            );
        }

        return response()->json($response);
    }
}
