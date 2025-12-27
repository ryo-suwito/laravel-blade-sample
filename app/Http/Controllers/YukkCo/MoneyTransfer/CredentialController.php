<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Constants\ModelTypeConstant as Type;
use App\Helpers\H;
use App\Services\API;
use Illuminate\Http\Request;

class CredentialController extends BaseController
{
    protected $service;
    protected $types =[
        Type::PARTNER,
        Type::BENEFICIARY,
    ];

    public function __construct()
    {
        $this->service = API::instance('client_management', 'client');
    }

    public function store(Request $request)
    {
        if(! in_array($request->entity_type, $this->types)) {
            H::flashFailed("Entity type is invalid!", true);

            return redirect()->back();
        }

        $response = $this->service->createClient([
            'entity_type' => $request->entity_type,
            'entity_id' => $request->entity_id,
            'permissions' =>["money_transfer"]
        ]);

        if(! $response->successful()) {
            return $this->getErrorAction(__CLASS__.".".__FUNCTION__, $response);
        }

        H::flashSuccess("Credential created successfully", true);

        return redirect()->back();
    }
}
