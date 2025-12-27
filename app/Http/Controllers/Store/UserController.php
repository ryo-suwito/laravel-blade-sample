<?php

namespace App\Http\Controllers\Store;

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

class UserController extends Controller
{
    private $service;

    private $roleService;

    private $userSettingService;

    private $partnerService;

    private $beneficiaryService;

    private $merchantBranchService;

    private $targetIdService;

    public function __construct(
        UserService $service,
        RoleService $roleService,
        UserSettingService $userSettingService,
        PartnerService $partnerService,
        BeneficiaryService $beneficiaryService,
        MerchantBranchService $merchantBranchService,
        TargetIdService $targetIdService
    )
    {
        $this->service = $service;
        $this->roleService = $roleService;
        $this->userSettingService = $userSettingService;
        $this->partnerService = $partnerService;
        $this->beneficiaryService = $beneficiaryService;
        $this->merchantBranchService = $merchantBranchService;
        $this->targetIdService = $targetIdService;

    }

    public function index(Request $request) {
        $filter = $request->only(['search', 'active', 'role_id', 'page', 'per_page']);

        $userResponse = $this->service->paginated($filter);
        $roleResponse = $this->roleService->paginated(['per_page' => 200]);

        apiResponseHandler($userResponse, false);
        apiResponseHandler($roleResponse, false);

        $data = array_merge($filter, [
            'users' => $userResponse->json('result'),
            'roles' => $roleResponse->json('result'),
            'current_page' => @$filter['page'] ?? 1,
            'last_page' => $userResponse->json('result.last_page'),
            'filter' => (object) $filter
        ]);
        
        return view('yukk_co.users.list', $data);
    }

    public function toggleActive(Request $request) {
        $response = $this->service->toggleActive($request->get('id'));

        apiResponseHandler($response);

        H::flashSuccess("Successfully toggling user.", true);
        return redirect()->back();
    }

    public function show(Request $request, $id) {
        $userResponse = $this->service->find($id);

        apiResponseHandler($userResponse, false);

        $user = $userResponse->json('result');

        $partner = array_filter($userResponse->json('result.user_roles.*.partner'), function($partner) {
            return !empty($partner);
        });
        if($partner){
            $partner = collect($partner)->unique('name')->toArray();
        }
        $merchantBranch = array_filter($userResponse->json('result.user_roles.*.merchant_branch'), function($merchantBranch) {
            return !empty($merchantBranch);
        });
        if($merchantBranch){
            $merchantBranch = collect($merchantBranch)->unique('name')->toArray();
        }
        $customers = array_filter($userResponse->json('result.user_roles.*.customer'), function($customer) {
            return !empty($customer);
        });
        if($customers){
            $customers = collect($customers)->unique('name')->toArray();
        }

        return view('yukk_co.users.detail', [
            'user' => $user,
            'partners' => $partner,
            'merchantBranches' => $merchantBranch,
            'customers' => $customers
        ]);
    }

    public function create(Request $request) {
        $roleResponse = $this->roleService->paginated(['per_page' => 200]);

        apiResponseHandler($roleResponse, false);

        $roles = [];
        foreach ($roleResponse->json('result.data') as $role) {
            $roles[] = [
                'id' => $role['id'],
                'name' => $role['name'],
                'target_type' => $role['target_type']
            ];
        }

        return view('yukk_co.users.create', [
            'roles' => $roleResponse->json('result.data')
        ]);
    }

    public function store(StoreRequest $request) {
        $validator = Validator::make($request->all(), [
            '_roles.*' => ['nullable', 'array', 'min:1', function($attribute, $value, $fail) {
                if ($value['target_type'] != 'YUKK_CO' && empty($value['targets'])) {
                    $fail('Please select corresponding target (Partner / Merchant Branch / Beneficary / etc');
                }
            }]
        ]);

        if ($validator->fails()) {
            H::flashFailed($validator->errors()->first(), true);
            return back()->withInput();
        }

        $body = $request->all();
        $body['username'] = $body['email'];

        foreach($request->get('_roles') ?? [] as $role) {
            if ($role['targets'] == []) {
                $body['roles'][] = [
                    'role_id' => $role['id'],
                    'target_id' => null,
                    'target_type' => $role['target_type']
                ];
            } else {
                foreach ($role['targets'] ?? [] as $target) {
                    $body['roles'][] = [
                        'role_id' => $role['id'],
                        'target_id' => $target,
                        'target_type' => $role['target_type']
                    ];
                }
            }
        }

        unset($body['role_ids']);
        unset($body['_roles']);

        $userResponse = $this->service->create($body);

        apiResponseHandler($userResponse, true, function($response) {
            H::flashFailed($response->json('status_message'), true);

            abort(
                back()->withInput()->withErrors($response->json('result')),
                $response->json('status_message')
            );
        });

        if ($userResponse->status() != 200) {
            H::flashFailed($userResponse->json('status_message'), true);
            return back()->withInput();
        }

        $activateSandboxResponse = $this->userSettingService->activateSandbox([
            'activate' => $request->get('sandbox_pg'),
            'user_id' => $userResponse->json('result.user.id')
        ]);

        apiResponseHandler($activateSandboxResponse, true, function ($response) {
            H::flashFailed($response->json('status_message'), true);

            abort(
                back()->withInput()->withErrors($response->json('result')),
                $response->json('status_message')
            );
        });

        H::flashSuccess($userResponse->json('status_message'), true);
        return redirect(route('cms.store.users.list'));
    }

    public function edit(Request $request, $id) {
        $userResponse = $this->service->find($id);
        $user = $userResponse->json('result');
        $user['settings'] = array_map(function($setting) {
            if($setting['name'] == 'PG_SANDBOX' && $setting['value'] == true) {
                return $setting['name'];
            }
        }, $user['settings']);

        $roleResponse = $this->roleService->paginated(['per_page' => 200]);

        apiResponseHandler($userResponse, false);

        $userRoles = [];
        foreach ($userResponse->json('result.roles') as $role) {
            $userRoles[] = "{$role['id']}|{$role['name']}|{$role['target_type']}";
        }

        $roles = [];
        $_roles = $roleResponse->json('result.data');
        foreach($_roles as $role) {
            $roles[] = "{$role['id']}|{$role['name']}|{$role['target_type']}";
        }

        $partner = array_filter($userResponse->json('result.user_roles.*.partner'), function($partner) {
            return !empty($partner);
        });
        if($partner){
            $partner = collect($partner)->unique('name')->toArray();
        }
        $merchantBranch = array_filter($userResponse->json('result.user_roles.*.merchant_branch'), function($merchantBranch) {
            return !empty($merchantBranch);
        });
        if($merchantBranch){
            $merchantBranch = collect($merchantBranch)->unique('name')->toArray();
        }
        $customer = array_filter($userResponse->json('result.user_roles.*.customer'), function($customer) {
            return !empty($customer);
        });
        if($customer){
            $customer = collect($customer)->unique('name')->toArray();
        }
        return view('yukk_co.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,

            'partners' => $partner,
            'merchantBranches' => $merchantBranch,
            'customers' => $customer
        ]);
    }

    public function reset(Request $request, $id) {
        return abort(401);
        /*$item = [
            'password' => $request->get('password')
        ];
        $reset = $this->service->resetPassword($id, $item);

        apiResponseHandler($reset);

        $responseJson = json_decode($reset);

        if ($responseJson->status_code == 6000) {
            H::flashSuccess($responseJson->status_message, true);

            return back();
        } else {
            H::flashFailed($responseJson->status_message, true);

            return back();
        }*/
    }

    public function update(UpdateRequest $request) {
        $validator = Validator::make($request->all(), [
            '_roles.*' => ['required', 'array', 'min:1', function($attribute, $value, $fail) {
                if ($value['target_type'] != 'YUKK_CO' && empty($value['targets'])) {
                    $fail('Please select corresponding target (Partner / Merchant Branch / Beneficary / etc');
                }
            }]
        ]);

        if ($validator->fails()) {
            H::flashFailed($validator->errors()->first(), true);
            return back()->withInput();
        }

        $roleResponse = $this->roleService->paginated([
            'ids' => $request->get('role_ids'),
            'per_page' => count($request->get('_roles'))
        ]);

        $roles = [];
        foreach($request->get('_roles') as $role) {
            if ($role['targets'] == []) {
                $roles[] = [
                    'role_id' => $role['id'],
                    'targets' => [],
                    'target_type' => $role['target_type']
                ];
            } else {
                $roles[] = [
                    'role_id' => $role['id'],
                    'targets' => $role['targets'],
                    'target_type' => $role['target_type']
                ];
            }
        }

        $body = $request->all();
        $body['username'] = $body['email'];
        $userResponse = $this->service->update($request->route('id'), $body);

        apiResponseHandler($userResponse, true, function($response) {
            H::flashFailed($response->json('status_message'), true);

            abort(
                back()->withInput()->withErrors($response->json('result')),
                $response->json('status_message')
            );
        });

        $syncRoleResponse = $this->service->syncRoles($request->route('id'), $roles);

        apiResponseHandler($syncRoleResponse, true, function($response) {
            H::flashFailed($response->json('status_message'), true);

            abort(
                back()->withInput()->withErrors($response->json('result')),
                $response->json('status_message')
            );
        });

        $activateSandboxResponse = $this->userSettingService->activateSandbox([
            'activate' => $request->get('sandbox_pg'),
            'user_id' => $request->route('id')
        ]);

        apiResponseHandler($activateSandboxResponse, true, function($response) {
            H::flashFailed($response->json('status_message'), true);

            abort(
                back()->withInput()->withErrors($response->json('result')),
                $response->json('status_message')
            );
        });

        if (!$userResponse->ok()) {
            return back()->withInput();
        }

        if (!$syncRoleResponse->ok()) {
            return back()->withInput();
        }

        H::flashSuccess($userResponse->json('status_message'), true);
        return redirect(route('cms.store.users.list'));
    }

    public function profile()
    {
        $temp_profile = $this->service->profile();

        apiResponseHandler($temp_profile, false);

        $profile = json_decode($temp_profile);

        if ($profile->status_code == 6000){
            return view('user.profile', compact('profile'));
        }else{
            H::flashFailed($profile->status_message, true);

            return view('global.default_api_response_not_ok');
        }
    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "phone" => "numeric",
            //"email" => "required|regex:/(.+)@(.+)\.(.+)/i",
            "full_name" => "required",
            "gender" => "required",
        ]);

        if (! $validator->fails()) {
            $item = $request->only(['password', 'full_name', 'phone', 'gender']);

            $res = $this->service->updateProfile($item);

            apiResponseHandler($res, true, function ($response){
                H::flashFailed($response->json("status_message"), true);
            });

            return redirect(route("cms.user.profile"));
        } else {
            H::flashFailed($validator->errors()->first(), true);
            return back()->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $old_password = $request->get('old_password');
        $password =  $request->get('new_password');
        $confirm_password =  $request->get('_password');

        $item = [
            'old_password' => $old_password,
            'password' => $password,
            '_password' => $confirm_password,
        ];

        $res = $this->service->changePassword($item);

        apiResponseHandler($res, true, function($response) {
            H::flashFailed($response->json("status_message"), true);
        });

        H::flashSuccess($res->json('status_message'), true);
        return redirect(route("cms.user.profile"));
    }

}
