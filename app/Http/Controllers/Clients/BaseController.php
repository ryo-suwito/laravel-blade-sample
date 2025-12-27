<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\CustomResponse;
use App\Helpers\S;

class BaseController extends \App\Http\Controllers\BaseController
{
    public function __construct() {
        $this->middleware(function($request, $next){
            //dd(S::getUserRole()->target_type);
            if(! S::getUserRole()->target_type == "YUKK_CO") {
                return abort(404);
            }
            return $next($request);
        });
    }

    public function getApiResponseNotOkDefaultResponse(CustomResponse $custom_response) {
        if ($custom_response->is_ok) {
            return $custom_response->status_message;
        } else {
            if ($custom_response->http_status_code == 200) {
                return view("global.default_api_response_not_ok", ["custom_response" => $custom_response]);
            } else if($custom_response->http_status_code == 401){
                session()->flush();
                return redirect(route('cms.login'));
            } else if ($custom_response->http_status_code == 404) {
                $custom_response->status_message ? $custom_response->status_message : __("cms.custom_response_404");
                return view("global.default_api_response_not_ok", ["custom_response" => $custom_response]);
            } else if ($custom_response->http_status_code == 500) {
                $custom_response->status_message = __("cms.custom_response_500");
                return view("global.default_api_response_not_ok", ["custom_response" => $custom_response]);
            } else {
                $custom_response->status_message = __("cms.custom_response_other", [
                    "http_status_code" => $custom_response->http_status_code,
                ]);
                return view("global.default_api_response_not_ok", ["custom_response" => $custom_response]);
            }
        }
    }

    public function actionUnauthorized($access_control = null) {
        return abort(401, __("cms.401_unauthorized_message", [
            "access_contol_list" => $access_control,
        ]));
    }
}
