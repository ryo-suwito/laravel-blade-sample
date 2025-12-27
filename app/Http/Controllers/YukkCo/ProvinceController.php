<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;

class ProvinceController
{
    public function listJson(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PROVINCE_LIST_YUKK_CO, [
        ]);

        if ($response->is_ok){
            return response()->json($response->result);
        }else{
            return response()->json([],400);
        }
    }
}
