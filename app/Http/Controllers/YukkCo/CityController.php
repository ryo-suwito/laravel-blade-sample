<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    public function listJson(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_CITY_LIST_YUKK_CO, [
            'form_params' => [
                'province_id' => $request->get('province_id')
            ]
        ]);

        if ($response->is_ok){
            return response()->json($response->result);
        }else{
            return response()->json([],400);
        }
    }
}
