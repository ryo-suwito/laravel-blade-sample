<?php

namespace App\Http\Controllers\Store;

use App\Constants\RoleTargetTypeConstant as TargetType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\User\ImportFileRequest;
use Illuminate\Http\Request;

class ImportUserController extends Controller
{
    protected $roles;

    protected $users;

    protected $partners;

    protected $merchantBranches;

    protected $beneficiaries;

    public function __construct()
    {
        $this->roles = api('store_management', 'role');
        $this->users = api('store_management', 'user');
        $this->partners = api('core_api', 'partner');
        $this->merchantBranches = api('core_api', 'merchant_branch');
        $this->beneficiaries = api('core_api', 'beneficiary');
    }

    public function showForm()
    {
        return view('store.user.import');
    }

    public function preview(ImportFileRequest $request)
    {
        $response = $this->users->bulkPreview($request->file('file'));

        apiResponseHandler($response, false, false);

        if (! $response->ok() && $response->status() != 422) {
            abort($response->status());
        }

        $users = $response->json('result.users');
        $errors = $response->json('result.errors');

        $this->fetchPartners($users);
        $this->fetchMerchantBranches($users);
        $this->fetchBeneficiaries($users);

        $users = collect($users)->map(function ($user) {
            return collect($user)->except([
                'role_ids', 'partner_ids', 'merchant_branch_ids', 'beneficiary_ids',
            ]);
        })->toArray();

        return view('store.user.import-preview', compact('users', 'errors'));
    }

    private function fetchPartners(array &$users)
    {
        $response = $this->partners->paginated([
            'id' => collect($users)->flatMap(function ($user) {
                return $user['partner_ids'];
            })->unique()->toArray(),
        ]);

        apiResponseHandler($response, false);

        $partners = $response->json('result.data');

        foreach ($users as $i => $user) {
            foreach ($user['roles'] as $j => $role) {
                if ($role['target_type'] == TargetType::PARTNER) {
                    $_partners = collect($partners)
                        ->whereIn('id', $user['partner_ids'])
                        ->map(function ($partner) {
                            return [
                                'id' => $partner['id'],
                                'name' => $partner['name'],
                            ];
                        })
                        ->values()
                        ->toArray();

                    $users[$i]['roles'][$j]['partners'] = $_partners;
                    $users[$i]['roles'][$j]['targets'] = $_partners;
                }
            }
        }
    }

    private function fetchMerchantBranches(array &$users)
    {
        $response = $this->merchantBranches->paginated([
            'id' => collect($users)->flatMap(function ($user) {
                return $user['merchant_branch_ids'];
            })->unique()->toArray(),
        ]);

        apiResponseHandler($response, false);

        $branches = $response->json('result.data');

        foreach ($users as $i => $user) {
            foreach ($user['roles'] as $j => $role) {
                if ($role['target_type'] == TargetType::MERCHANT_BRANCH) {
                    $_branches = collect($branches)
                        ->whereIn('id', $user['merchant_branch_ids'])
                        ->map(function ($branch) {
                            return [
                                'id' => $branch['id'],
                                'name' => $branch['name'],
                            ];
                        })
                        ->values()
                        ->toArray();

                    $users[$i]['roles'][$j]['merchant_branches'] = $_branches;
                    $users[$i]['roles'][$j]['targets'] = $_branches;
                }
            }
        }
    }

    private function fetchBeneficiaries(array &$users)
    {
        $response = $this->beneficiaries->paginated([
            'id' => collect($users)->flatMap(function ($user) {
                return $user['beneficiary_ids'];
            })->unique()->toArray(),
        ]);

        apiResponseHandler($response, false);

        $beneficiaries = $response->json('result.data');

        foreach ($users as $i => $user) {
            foreach ($user['roles'] as $j => $role) {
                if ($role['target_type'] == TargetType::CUSTOMER) {
                    $_beneficiaries = collect($beneficiaries)
                        ->whereIn('id', $user['beneficiary_ids'])
                        ->map(function ($beneficiary) {
                            return [
                                'id' => $beneficiary['id'],
                                'name' => $beneficiary['name'],
                            ];
                        })
                        ->values()
                        ->toArray();

                    $users[$i]['roles'][$j]['beneficiaries'] = $_beneficiaries;
                    $users[$i]['roles'][$j]['targets'] = $_beneficiaries;
                }
            }
        }
    }
}
