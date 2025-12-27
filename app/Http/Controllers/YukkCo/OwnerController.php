<?php

namespace App\Http\Controllers\YukkCo;

use App\Actions\Owner\CreateOwner;
use App\Actions\Owner\OCR;
use App\Actions\Owner\UpdateOwner;
use App\Helpers\S;
use GuzzleHttp\Client;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\BaseController;
use App\Http\Requests\YukkCo\Owner\StoreOwnerRequest;
use App\Http\Requests\YukkCo\Owner\UpdateOwnerRequest;
use App\Http\Requests\YukkCo\Owner\VerifyOwnerRequest;
use Yajra\DataTables\Facades\DataTables;

class OwnerController extends BaseController
{
    public function index(Request $request)
    {    
        $page = $request->get("page", 1);
        $name = $request->get("name", null);

        $query_params = [
            "page" => $page,
            "per_page" => $request->get("per_page", 10),
        ];

        if ($name) {
            $query_params["keyword"] = $name;
        }

        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
            "query" => $query_params,
        ]);
        if($data){
            $access_control = json_decode(S::getUserRole()->role->access_control);

            return view("yukk_co.owners.list", [
                "data" => $data,
                "name" => $name,
                "access_control" => $access_control
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($data);
        }
    }   

    public function create(Request $request)
    {
        $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
            "query" => [],
        ]);

        $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
            "query" => [],
        ]);

        return view('yukk_co.owners.create', [
                'banks' => $banks->result,
                'cities' => $cities->result
            ]
        );
    }

    public function edit($id)
    {   
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_ITEM, [
            "query" => [
                'id' => $id
            ],
        ]);

        $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
            "query" => [],
        ]);

        $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
            "query" => [],
        ]);
  
        return view('yukk_co.owners.edit', [
            "owner" => $response->result,
            "cities" => $cities->result,
            "banks" => $banks->result
        ]);
    }

    public function show($id)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_ITEM, [
            "query" => [
                'id' => $id
            ],
        ]);

        $cities = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CITY_GET_LIST, [
            "query" => [],
        ]);

        $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, [
            "query" => [],
        ]);
        
        return view('yukk_co.owners.show', [
            "owner" => $response->result,
            "cities" => $cities->result,
            "banks" => $banks->result
        ]);
    }

    public function store(StoreOwnerRequest $request, CreateOwner $service)
    {
        $response = $service->save($request->validated());

        // apiResponseHandler($response, false);

        H::flashSuccess('Owner has been successfully saved. Please wait for approval.', true);

        return redirect()->route('yukk_co.owners.list');
    }

    public function update(UpdateOwnerRequest $request, $id, UpdateOwner $service)
    {
        $response = $service->update($id, $request->validated());

        H::flashSuccess('Owner has been successfully updated. Please wait for approval.', true);

        return redirect()->route('yukk_co.owners.list')->with('success', 'Owner updated successfully');
    }

    public function verify(VerifyOwnerRequest $request, CreateOwner $service) {
        $response = $service->verify($request->validated());

        return response()->json($response->json(), $response->status());
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

    public function data(Request $request)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
            "query" => [
                'per_page' => ($request->length) ? $request->length : 10,
                "keyword" => $request->search['value'],
                "type" => isset($request->type) ? $request->type : null,
                "active" => isset($request->status) ? $request->status : null,
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
        if (in_array('MASTER_DATA.OWNERS.EDIT', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.owners.edit", $item->id) . '"
            class="dropdown-item"><i class="icon-search4"></i>
            Edit
            </a>';
                            
            $features = $features . '<form method="POST" action="' . route("yukk_co.owners.toggle", $item->id) . '" class="dropdown-item">';
            $features = $features . '<input type="hidden" name="_token" value="' . csrf_token() . '">';
            $features = $features . '<input type="hidden" name="id" value="' . $item->id . '">';
            if($item->active) {
                $features = $features . '<button type="submit" name="status" value="0" style="cursor: pointer; background-color: transparent; border: none; color: #fff; padding: 0; width: 100%; text-align: left;" data-status="0">';
                $features = $features . '<i class="icon-cross2" style="margin-right: 1rem;"></i>Set Inactive</button>';
            } else {
                $features = $features . '<button type="submit" name="status" value="1" style="cursor: pointer; background-color: transparent; border: none; color: #fff; padding: 0; width: 100%; text-align: left;" data-status="1">';
                $features = $features . '<i class="icon-checkmark3" style="margin-right: 1rem;"></i>Set Active</button>';
            }
            $features = $features . '</form>';                 
        }
        if (in_array("MASTER_DATA.OWNERS.VIEW", $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.owners.detail", $item->id) . '"
            class="dropdown-item"><i class="icon-zoomin3"></i>
            Detail
        </a>';
        }
        return $features;
    }

    public function scanKtp(Request $request, OCR $ocr)
    {
        $response = $ocr->scanKtp($request->file('file_ktp'));

        if ($response->json('status_code') == 40001) {

        }

        return response()->json($response->json(), 200);
    }

    public function toggle(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_OWNER_TOGGLE_STATUS, [
            "query" => [
                'id' => $request->id,
                'active' => $request->status
            ],
        ]);

        if ($response->is_ok) {
            H::flashSuccess('Owner has been successfully updated. Please wait for approval.', true);
            return redirect()->route('yukk_co.owners.list');
        } else {
            H::flashFailed('Failed to update owner status.', true);
            return redirect()->route('yukk_co.owners.list');
        }
    }

    public function getOwnerDetails($id)
    {
        $owner = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_ITEM, [
            "query" => [
                'id' => $id
            ],
        ]);
        
        if ($owner) {
            return response()->json([
                'owner' => $owner->result
            ]);
        }
    }
}
