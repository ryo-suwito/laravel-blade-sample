<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    public function listJson(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_REGION_LIST_YUKK_CO, [
            'form_params' => [
                'city_id' => $request->get('city_id')
            ]
        ]);

        if ($response->is_ok){
            return response()->json($response->result);
        }else{
            return response()->json([],400);
        }
    }
}
