<?php

namespace App\Http\Controllers\YukkCo;

use App\Http\Controllers\Controller;
use App\Services\API;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PartnerProviderCredentialController extends Controller
{
    protected $credential;
    protected $partner;
    protected $paymentChannel;
    protected $provider;

    public function __construct()
    {
        $this->credential = API::instance('payment_gateway', 'credential');
        $this->partner = API::instance('payment_gateway', 'partner');
        $this->paymentChannel = API::instance('payment_gateway', 'payment_channel');
        $this->provider = API::instance('payment_gateway', 'provider');
    }

    public function service(string $serviceName, string $method, array $args = [])
    {
        $customValidationMethod = ['store', 'update'];
        $failedCallback = null;

        $response = $this->{$serviceName}->{$method}(...$args);
        
        if(in_array($method,$customValidationMethod)) {
            if($response->status() == 422) {
                $failedCallback = function() use ($response) {
                    toast('error', array_values($response->json('result'))[0][0]);

                    return back();
                };
            }
        }

        apiResponseHandler($response, false, $failedCallback);

        return  $response->json('result');
    }

    public function index($partner_id)
    {
        $partner = $this->service('partner', 'get', [$partner_id]);

        $credentials = $this->service('credential', 'all', [$partner_id]);

        return view('yukk_co.partner_provider_credential.index', [
            'partner' => $partner,
            'providers' => $credentials
        ]);
    }

    public function create($partner_id)
    {
        $partner = $this->service('partner', 'get', [$partner_id]);

        $providers = $this->service('provider', 'all');

        $partnerPaymentChannels = $this->service('paymentChannel', 'all', ['partner', $partner_id]);

        $partnerPaymentChannels = implode("," ,array_map(function($paymentChannel) {
            return $paymentChannel['code'];
        }, $partnerPaymentChannels));

        return view('yukk_co.partner_provider_credential.create', [
            "partner" => $partner,
            "providers" => $providers,
            "partnerPaymentChannels" => $partnerPaymentChannels
        ]);
    }

    public function show($partner_id, $token)
    {
        $partner = $this->service('partner', 'get', [$partner_id]);

        $providerCredential = $this->service('credential', 'show', [$partner_id, $token]);

        $partnerPaymentChannels = $this->service('paymentChannel', 'all', ['partner', $partner_id]);

        $partnerPaymentChannels = array_map(function($paymentChannel) {
            return $paymentChannel['code'];
        }, $partnerPaymentChannels);

        $providerPaymentChannels = $this->service('paymentChannel', 'all', ['provider', $providerCredential['id']]);

        return view('yukk_co.partner_provider_credential.show-edit', [
            'partner' => $partner,
            'providerCredential' => $providerCredential,
            'token' => $token,
            'partnerPaymentChannels' => $partnerPaymentChannels,
            'providerPaymentChannels' => $providerPaymentChannels,
        ]);   
    }

    public function store(Request $request, $partner_id)
    {
        $name = $request->name;
        $value = $request->value;
        $provider = explode("|", $request->provider)[0];

        if(is_null($request->get('payment_channels')))
        {
            toast('error','Payment channel must be choose at least one channel.');

            return redirect()->back()->withInput();
        }

        if($provider == 'YUKK'){
            $array_credential = array(
                'value' => []
            );
        }else if($provider == 'OTTOPAY'){
            $array_credential = [];
        }else{
            $count = sizeof($name);
            $array_credential = [];

            for ($i=0; $i < $count; $i++) { 
                
                if($name[$i] != null && $value[$i] != null){
                    $array_credential += array(
                        $name[$i] => $value[$i]
                    );
                }
            }
        }

        $this->service('credential', 'store', [$partner_id, array_merge($request->only([
            'payment_channels', 
            'status',
        ]), [
            'provider' => explode("|", $request->provider)[1]
        ], [
            'credential' => json_encode($array_credential)
        ])]);

        toast('success','New credential already saved.');
        return redirect()->route("cms.yukk_co.partners.credentials.index", ["partner_id" => $partner_id]);
    }

    public function edit($partner_id, $token)
    {
        $partner = $this->service('partner', 'get', [$partner_id]);

        $providerCredential = $this->service('credential', 'show', [$partner_id, $token]);

        $partnerPaymentChannels = $this->service('paymentChannel', 'all', ['partner', $partner_id]);

        $partnerPaymentChannels = array_map(function($paymentChannel) {
            return $paymentChannel['code'];
        }, $partnerPaymentChannels);

        $providerPaymentChannels = $this->service('paymentChannel', 'all', ['provider', $providerCredential['id']]);

        return view('yukk_co.partner_provider_credential.show-edit', [
            'partner' => $partner,
            'providerCredential' => $providerCredential,
            'token' => $token,
            'partnerPaymentChannels' => $partnerPaymentChannels,
            'providerPaymentChannels' => $providerPaymentChannels,
        ]);
    }

    public function update(Request $request, $partner_id, $token)
    {        
        $name = $request->name;
        $value = $request->value;
        $provider = explode("|", $request->provider)[0];
        
        $array_credential = [];

        if($provider == 'YUKK'){
            $merchant_branch_id = $request->merchant_branch_id;
            $client_id = $request->client_id;
            $client_secret = $request->client_secret;

            if($merchant_branch_id != null){
                for ($i=0; $i < sizeof($merchant_branch_id); $i++) { 
                
                    if($merchant_branch_id[$i] != null){
                        $array_value = array(
                            'merchant_branch_id'    => $merchant_branch_id[$i],
                            'client_id'             => $client_id[$i],
                            'client_secret'         => $client_secret[$i]
                        );
                        array_push($array_credential, $array_value);
                    }
                }
            }

            $array_credential = array(
                'value' => $array_credential
            );
        }else{
            $count = sizeof($name);
            for ($i=0; $i < $count; $i++) {

                if($name[$i] != null && $value[$i] != null){
                    $array_credential += array(
                        $name[$i] => $value[$i]
                    );
                }
            }
        }
        
        $this->service('credential', 'update', [$partner_id, $token, 
            array_merge($request->all(), 
                ['credential' => json_encode($array_credential)]
            )
        ]);

        toast('success','Credential already updated.');
        return redirect()->route("cms.yukk_co.partners.credentials.index", ["partner_id" => $partner_id]);
    }

}
