<?php

namespace App\Http\Controllers\JSON\MoneyTransfer;

use App\Services\MoneyTransfer\PartnerSettingService;
use Illuminate\Http\Request;

class EntityController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new PartnerSettingService();
    }

    public function index(Request $request)
    {
        $response = $this->service->getOptionEntities([
            'tag' => $request->get('tag', null),
            'search' => $request->get('search', null),
            'assigned' => 0,
        ]);

        if($response->status() && $response->json('status_code') == 200) {
            return response()->json($response->json('result'));
        } else {
            return $this->getErrorAction('Money Transfer - Partner Setting - Json - Entity', $response);
        }
    }

}
