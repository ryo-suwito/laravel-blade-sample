<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Services\MoneyTransfer\BankService;
use App\Services\MoneyTransfer\ProviderService;
use Illuminate\Http\Request;

class ProviderController extends BaseController
{
    protected $service;
    protected $bankService;

    public function __construct()
    {
        $this->service = new ProviderService();
        $this->bankService = new BankService();
    }

    public function index(Request $request)
    {
        $response = $this->service->list();

        if($response->status() && $response->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Providers', $response);
        } 

        return view('yukk_co.money_transfer.providers.index', [
            'providers' => $response->json('result'),
        ]);
    }

    public function edit($id) 
    {
        $configResp = $this->service->getConfig();

        if($configResp->status() && $configResp->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Provider Config', $configResp);
        } 

        $response = $this->service->show($id);

        if($response->status() && $response->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Provider', $response);
        } 

        $bankAssignResp = $this->bankService->assigned('providers', $id);

        if($bankAssignResp->status() && $bankAssignResp->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Banks Assigned', $bankAssignResp);
        } 

        $bankNonAssignResp = $this->bankService->nonAssigned('providers', $id);

        if($bankNonAssignResp->status() && $bankNonAssignResp->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Banks Assigned', $bankNonAssignResp);
        } 

        $inquiryProvidersResponse = $this->service->getInquiryProviders($id);

        if($inquiryProvidersResponse->status() && $inquiryProvidersResponse->json('status_code') != 200) {
            return $this->getErrorAction('Money Transfer - Manage Provider - Get Provider Inquiry', $inquiryProvidersResponse);
        } 

        return view('yukk_co.money_transfer.providers.setting', [
            'provider' => $response->json('result'),
            'config' =>  $configResp->json('result'),
            'list_non_assigned' => $bankNonAssignResp->json('result'),
            'list_assigned' => $bankAssignResp->json('result'),
            'inquiryProviders' => $inquiryProvidersResponse->json('result'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $settings = [];

        foreach($request->settings as $setting) {
            $settings[] = [
                'name' => $setting['name'],
                'value' => $this->getValidConfigValue($setting)
            ];
        }
    
        $response = $this->service->update($id, [
            "settings" => $settings
        ]);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("Provider setting updated successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Provider Setting - Update', $response);
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
            "banks" => $request->selectedPM,
            "provider_id" => $request->provider_id
        ];

        $response = $this->service->bankStore($query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("New payment channel successfully added", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Manage Provider - Store Payment Channel', $response);
        }
    }

    public function bankUpdate(Request $request, $id)
    {
        $query_params = [
            "actions" => $request->actions,
        ];

        $response = $this->service->bankUpdate($id, $query_params);

        if($response->status() && $response->json('status_code') == 200) {
            H::flashSuccess("Payment channels updated successfully", true);

            return redirect()->back();
        } else {

            return $this->getErrorAction('Money Transfer - Manage Provider - Update Payment Channels', $response);
        }
    }
}
