<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends BaseController
{
    protected $clients;

    public function __construct()
    {
        $this->clients = api('client_management', 'client');
    }

    public function index(Request $request)
    {
        $query = [
            'per_page' => $request->get('per_page', 10),
            'page' => $request->get('page'),
            'status' => $request->get('status'),
            'entity' => $request->get('type'),
            'field' => $request->get('field'),
            'keyword' => $request->get('keyword')
        ];

        $response = $this->clients->paginate($query);

        if ($response->status() == '401' || $response->status() == '403'){
            H::flashFailed($response->json('status_message'), true);

            return back();
        }elseif($response->status() == '500'){
            H::flashFailed('Internal Server Error', true);

            return back();
        }

        $result = (object) $response['result'];

        return view('client.index', [
            'client_list' => $result->data,
            'status' => $query['status'],
            'entity' => $query['entity'],
            'field' => $query['field'],
            'keyword' => $query['keyword'],

            'from' => $result->from,
            'to' => $result->to,
            'total' => $result->total,

            'per_page' => $query['per_page'],
            'current_page' => $result->current_page,
            'last_page' => $result->last_page,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $response = $this->clients->find(
            $id
        );

        apiResponseHandler($response, false);

        if($response->status() == '404' || $response->status() == '500') {
            H::flashFailed($response->json('status_message'), true);

            return back();
        }

        $permission = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CLIENT_MANAGEMENT_GET_PERMISSION_LIST, [
            'query' => [
                'grouping' => 1
            ],
        ]);
        if ($permission->http_status_code == '403'){
            H::flashFailed('Forbidden Permission', true);

            return back();
        }

        $result = (object) $response->json('result');

        $permission_ids = collect($result->owner['permissions'])->map(function ($permission) {
            return $permission['id'];
        });

        return view('client.edit', [
            'client' => $result,
            'permissions' => $permission->result,
            'permission_ids' => $permission_ids->toArray()
        ]);
    }

    public function detail(Request $request, $id)
    {
        $response = $this->clients->find(
            $id
        );
        apiResponseHandler($response, false);

        if($response->status() == '404' || $response->status() == '500') {
            H::flashFailed($response->json('status_message'), true);

            return back();
        }

        $permission = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CLIENT_MANAGEMENT_GET_PERMISSION_LIST, [
            'query' => [
                'grouping' => 1
            ],
        ]);
        if ($permission->http_status_code == '403'){
            H::flashFailed('Forbidden Permission', true);

            return back();
        }

        $result = (object) $response->json('result');

        $permission_ids = collect($result->owner['permissions'])->map(function ($permission) {
            return $permission['id'];
        });

        return view('client.detail', [
            'client' => $result,
            'permissions' => $permission->result,
            'permission_ids' => $permission_ids->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'nullable|array',
            'revoked' => 'nullable|in:0,1',
            'client_id' => 'nullable',
            'client_secret' => 'nullable',
            'public_key' => 'nullable',
        ]);

        if ($validator->fails()) {
            H::flashFailed($validator->errors()->first(), true);

            return back();
        }

        $data = $request->all();

        $response = $this->clients->update(
            $id, $data
        );

        if ($response->status() == 422) {
            H::flashFailed($response->json()['message'], true);

            return back();
        };

        apiResponseHandler($response, false);

        H::flashSuccess('Success Edit', true);
        return redirect()->route('client_credential.detail', $response['result']['id']);
    }

    public function create(Request $request)
    {
        $permission = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CLIENT_MANAGEMENT_GET_PERMISSION_LIST, [
            'query' => [
                'grouping' => 1
            ],
        ]);

        if ($permission->is_ok){
            return view('client.create', [
                'permissions' => $permission->result,
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($permission);
        }
    }

    public function store(Request $request)
    {
        $response = ApiHelper::requestGeneral('POST', ApiHelper::END_POINT_CLIENT_MANAGEMENT_STORE_CLIENT_PERMISSION, [
            'form_params' => [
                'entity_id' => $request->get('entity_id'),
                'entity_type' => $request->get('type'),
                'revoked' => $request->get('status'),
                'public_key' => $request->get('public_key'),
                'permissions' => $request->get('permission')
            ]
        ]);

        if ($response->http_status_code == '201'){
            H::flashSuccess($response->status_message, true);
            return redirect(route('client_credential.detail', $response->result->id));
        }else{
            H::flashFailed($response->status_message, true);
            return back()->withInput();
        }
    }

    public function getCustomerJsonSelect2(Request $request)
    {
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CLIENT_MANAGEMENT_GET_CUSTOMER_LIST, [
            "query" => [
                "search" => $request->search,
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
        $data = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_CLIENT_MANAGEMENT_GET_PARTNER_LIST, [
            "query" => [
                "search" => $request->search,
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
}
