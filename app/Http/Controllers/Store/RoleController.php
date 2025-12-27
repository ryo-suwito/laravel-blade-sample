<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Role\StoreRequest;
use App\Http\Requests\Store\Role\UpdateRequest;
use App\Services\API;
use App\Services\Paginator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roles;

    protected $role = null;

    protected $accessControls;

    public function __construct()
    {
        $this->roles = API::instance('store_management', 'role');
        $this->accessControls = API::instance('cms_api', 'access_control');
    }

    public function index(Request $request)
    {
        $response = $this->roles->paginated([
            'page' => $request->get('page') ?? 1
        ]);

        apiResponseHandler($response, false);

        $roles = Paginator::fromResponse($response);

        if ($request->expectsJson()) {
            return response([
                'roles' => $roles->items(),
            ]);
        }
        return view('store.role.index', compact('roles'));
    }

    public function create()
    {
        $data = $this->prepareResources();

        return view('store.role.create', $data);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $response = $this->roles->create($data);

        apiResponseHandler($response);

        return redirect()->route('store.roles.index');
    }

    public function show($id)
    {
        $response = $this->roles->find($id);

        apiResponseHandler($response, false);

        $this->role = $response->json('result');
        $data = $this->prepareResources('access_controls');
        $data['role'] = $this->role;

        return view('store.role.show', $data);
    }

    public function edit($id)
    {
        $response = $this->roles->find($id);

        apiResponseHandler($response, false);

        $this->role = $response->json('result');
        $data = $this->prepareResources();
        $data['role'] = $this->role;

        return view('store.role.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $response = $this->roles->find($id);

        apiResponseHandler($response, false);

        $data = $request->validated();

        $response = $this->roles->update($id, $data);

        apiResponseHandler($response);

        return redirect()->route('store.roles.index');
    }

    private function prepareResources(...$resources)
    {
        $resources = empty($resources)
            ? ['target_types', 'access_controls']
            : $resources;

        $targetTypes = null;
        $accessControls = null;

        if (in_array('target_types', $resources)) {
            $response = $this->roles->getTargetTypes();

            apiResponseHandler($response, false)->failedView();

            $targetTypes = $response->json('result');
        }

        if (in_array('access_controls', $resources)) {
            $response = $this->accessControls->grouping($this->role['target_type'] ?? null);

            apiResponseHandler($response, false)->failedView();

            $accessControls = $response->json('result');
        }

        return compact('targetTypes', 'accessControls');
    }
}
