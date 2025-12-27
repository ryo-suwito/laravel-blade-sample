<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Services\API;
use App\Services\MoneyTransfer\BankService;
use App\Services\MoneyTransfer\PartnerSettingService;
use Illuminate\Http\Request;

class PartnerSettingController extends BaseController
{
    protected $service;
    protected $bankService;
    protected $settingService;

    public function __construct()
    {
        $this->service = new PartnerSettingService();
        $this->bankService = new BankService();
        $this->settingService = API::instance('money_transfer', 'setting');
    }

    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $search = $request->get("search", null);
        $tag = $request->get("tag", null);

        $query_params = [
            "page" => $page,
            "search" => $search,
            "tag" => $tag,
        ];

        $response = $this->service->getEntities($query_params);

        if($response->status() && $response->json('status_code') == 200) {

            $nonAssignedResponse = $this->service->getOptionEntities([
                    'assigned' => 0
            ]);

            if($nonAssignedResponse->status() && $nonAssignedResponse->json('status_code') == 200) {

                return view('yukk_co.money_transfer.partners.index', [
                    'settings' => $response->json('result.data'),
                    'current_page' => $response->json('result.current_page'),
                    'last_page' => $response->json('result.last_page'),
                    'total' => $response->json('result.total'),
                    'non_assigned_entities' => $nonAssignedResponse->json('result'),
                ]);
            } else {

                return $this->getErrorAction('Money Transfer - Partner Setting - Get Non Assigned Partner', $nonAssignedResponse);
            }    
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Get Partners', $response);
        }
    }

    public function store(Request $request)
    {
        if(is_null($request->selectedPartners) && is_null($request->selectedBeneficiaries)) {
            H::flashFailed("At least choose one user!", true);

            return redirect()->back();
        }

        $query_params = [];

        $query_params["entities"][0] = $request->selectedPartners;
        $query_params["entities"][1] = $request->selectedBeneficiaries;

        $response = $this->service->store($query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("New users setting created successfully", true);

            return redirect()->back();
        } else {
            
            return $this->getErrorAction('Money Transfer - Partner Setting - Store', $response);
        }
    }

    public function edit(Request $request, $id)
    {
        $relationType = [
            'PARTNER' => 'partners',
            'BENEFICIARY' => 'beneficiaries',
        ];

        $configResponse = $this->service->getConfig();

        if($configResponse->status() && $configResponse->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Partner Setting - Get Config', $configResponse);
        }

        $response = $this->service->show($id, [
            'tag' => $request->get('tag')
        ]);

        if($response->status() && $response->json('status_code') == 200) {

            $bankAssignedResponse = $this->bankService->assigned($relationType[$request->get('tag')], $id);

            if($bankAssignedResponse->status() && $bankAssignedResponse->json('status_code') == 200) {

                $bankNonAssignedResponse = $this->bankService->nonAssigned($relationType[$request->get('tag')], $id);
    
                if($bankNonAssignedResponse->status() && $bankNonAssignedResponse->json('status_code') == 200) {

                    $getCredentialResponse = $this->service->getCredential([
                        'type' => $request->get('tag'),
                        'id' => $id,
                    ]);

                    if($getCredentialResponse->ok()) {

                        $getSettingResponse = $this->settingService->get('PPN');

                        if($getSettingResponse->ok()) {
                            
                            return view('yukk_co.money_transfer.partners.setting', [
                                'settings' => $response->json('result'),
                                'list_assigned' => $bankAssignedResponse->json('result'),
                                'list_non_assigned' => $bankNonAssignedResponse->json('result'),
                                'config' => $configResponse->json('result'),
                                'ppn_percentage' => $getSettingResponse->json('result.PPN'),
                                'cred' => $getCredentialResponse->json('result'),
                            ]);
                        }
                    } else {
                        return $this->getErrorAction('Money Transfer - Entity Setting - Get Entity Credential', $getCredentialResponse);
                    }
                    
                } else {

                    return $this->getErrorAction('Money Transfer - Partner Setting - Get Payment Channel Non Assigned', $bankAssignedResponse);
                }
            } else {

                if($bankAssignedResponse->status() == 422) {
                    H::flashFailed($bankAssignedResponse->json('message'), true);
                    return redirect()->back();
                }

                return $this->getErrorAction('Money Transfer - Partner Setting - Get Payment Channel Assigned', $bankAssignedResponse);
            }
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Show', $response);
        }
    }

    public function update(Request $request, $id)
    {
        $settings = [];

        foreach($request->settings as $setting) {

            if($setting['type'] == 'integer' || $setting['type'] == 'float' ) {
                if($setting['view'] == 'custom.external_fee') {
                    if(($setting['value'] * 100 / ($setting['ppn'] + 100)) <= $setting['internal_fee']) {
                        H::flashFailed("DPP External ". $setting['name'] ." must be higher than MDR Internal", true);

                        return redirect()->back();
                    }
                }
            }

            $settings[] = [
                'name' => $setting['name'],
                'value' => $this->getValidConfigValue($setting)
            ];
        }
    
        $response = $this->service->update($id, [
            "settings" => $settings,
            "tag" => $request->get('tag'),
        ]);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("Partner setting updated successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Update', $response);
        }
    }

    public function userStore(Request $request)
    {
        $query_params = [
            "name" => $request->name,
            "email" => $request->email,
            "partner_id" => $request->partner_id
        ];

        $response = $this->service->userStore($query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("User add successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Store User', $response);
        }
    }

    public function bankStore(Request $request)
    {
        if($request->selectedPM == null) {
            H::flashFailed("Payment channel has not been selected", true);

            return redirect()->back();
        }

        $query_params = [
            "created_by" => session()->get("user")->full_name,
            "bank_ids" => $request->selectedPM,
            "id" => $request->id,
            "tag" => $request->tag,
        ];

        $response = $this->service->bankStore($query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("New payment channel successfully added", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Store Payment Channel', $response);
        }
    }

    public function bankUpdate(Request $request, $id)
    {
        $query_params = [
            "actions" => $request->actions,
            "tag" => $request->tag,
        ];

        $response = $this->service->bankUpdate($id, $query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("Payment channels updated successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Update Payment Channels', $response);
        }
    }

    public function userUpdate(Request $request, $id)
    {

        $query_params = [
            "name" => $request->user_login_name,
            "email" => $request->user_login_email,
            "active" => isset($request->user_login_status) ? 1 : 0,
        ];

        $response = $this->service->userUpdate($id, $query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("User updated successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Partner Setting - Update User', $response);
        }
    }

}
