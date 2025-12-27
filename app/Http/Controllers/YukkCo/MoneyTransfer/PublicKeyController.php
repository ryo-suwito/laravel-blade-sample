<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\FormatPublicKey;
use App\Constants\ModelTypeConstant as Type;
use App\Helpers\H;
use App\Services\API;
use Illuminate\Http\Request;

class PublicKeyController extends BaseController
{
    protected $service;
    protected $servicePublicKey;
    protected $types =[
        Type::PARTNER,
        Type::BENEFICIARY,
    ];

    public function __construct()
    {
        $this->service = API::instance('client_management', 'client');
        $this->servicePublicKey = new FormatPublicKey();
    }

    public function store(Request $request)
    {
        if (! is_null($request->public_key)) {
            if (! $this->rsaValidation($this->servicePublicKey->format($request->public_key))) {
                H::flashFailed("Public Key is invalid!", true);
    
                return redirect()->back();
            }
        }
        
        if (! in_array($request->entity_type, $this->types)) {
            H::flashFailed("Entity type is invalid!", true);

            return redirect()->back();
        }

        $response = $this->service->createPublicKey([
            'service' => 'ALL',
            'entity_type' => $request->entity_type,
            'entity_id' => $request->entity_id,
            'public_key' => is_null($request->public_key) ? null : $this->servicePublicKey->format($request->public_key)
        ]);

        if(! $response->successful()) {
            return $this->getErrorAction(__CLASS__.".".__FUNCTION__, $response);
        }

        H::flashSuccess("Public key created successfully", true);

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if (! is_null($request->public_key)) {
            if(! $this->rsaValidation($this->servicePublicKey->format($request->public_key))) {
                H::flashFailed("Public Key is invalid!", true);

                return redirect()->back();
            }
        }

        if(! in_array($request->entity_type, $this->types)) {
            H::flashFailed("Entity type is invalid!", true);

            return redirect()->back();
        }

        $response = $this->service->updatePublicKey($id, [
            'public_key' => is_null($request->public_key) ? null : $this->servicePublicKey->format($request->public_key)
        ]);

        if(! $response->successful()) {
            return $this->getErrorAction(__CLASS__.".".__FUNCTION__, $response);
        }

        H::flashSuccess("Public key updated successfully", true);

        return redirect()->back();
    }

    protected function rsaValidation($key)
    {
        return openssl_get_publickey($key);
    }
}
