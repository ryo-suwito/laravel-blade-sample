<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 04-Aug-21
 * Time: 00:07
 */

namespace App\Http\Controllers\Partners;


use App\Helpers\CustomResponse;
use App\Helpers\S;

class BaseController extends \App\Http\Controllers\BaseController {

    public function __construct() {
        $this->middleware(function($request, $next){
            //dd(S::getUserRole()->target_type);
            if(! S::getUserRole()->target_type == "PARTNER") {
                return abort(404);
            }
            return $next($request);
        });
    }

    public function getApiResponseNotOkDefaultResponse(CustomResponse $custom_response) {
        if ($custom_response->is_ok) {

        } else {
            return view("global.default_api_response_not_ok", ["custom_response" => $custom_response]);
        }
    }
}