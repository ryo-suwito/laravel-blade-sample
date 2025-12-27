<?php

namespace App\Http\Controllers;

use App\Helpers\S;
use App\Services\StoreManagement\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends BaseController
{
    //
    public function index() {

        $user = S::getUser();
        $user_email = $user->email;
        $banner_live = false;
        $url_request_live = "";

        $on_boarding = '';
        $on_boarding_cache = Cache::get('on_boarding_pages');

        $env_app = config('app.mode');

        if($on_boarding_cache == null){
            Cache::put('on_boarding_pages',1);
        }

        try {
            $on_boarding_res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->withoutVerifying()->get(config('website.api_base_url').config('website.end_point.get_on_boarding_list'));
            if ($on_boarding_res->status() == 200) {
                $on_boarding = $on_boarding_res->json();
            }
        }catch (\Exception $e){
            Log::error("Hit API On Boarding Pages:" ,[
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
            ]);
        }

        if ($env_app !== 'production'){
            try {
                $res = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->withoutVerifying()->post(config('services.app.production.url').config('services.app.production.end_point.production_check'),[
                    'username' => $user_email
                ]);
                if ($res->status() == 204){
                    try {
                        $res = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json'
                        ])->get(config('inbound.api_base_url').config('inbound.end_point.check_request_live').$user_email);
                        if($res->status() == 200){
                            $response =Http::get(config('website.api_base_url').config('website.end_point.encrypt_email').$user_email);
                            if ($response->status() == 200){
                                $banner_live = true;
                                $encryption = $response->json();
                                $url_request_live = config('website.base_url')."register/".$encryption."/production?token=".session()->get("jwt_token");
                            }else{
                                throw new \Exception($response->body(), $response->status());
                            }
                        }else{
                            throw new \Exception($res->body(), $res->status());
                        }
                    } catch  (\Exception $e){
                        Log::error("Hit API Check Request Live:" ,[
                            "message" => $e->getMessage(),
                            "code" => $e->getCode(),
                        ]);
                    };
                }
            }catch (\Exception $e){
                Log::error("Hit API in Store Management:" ,[
                    "message" => $e->getMessage(),
                    "code" => $e->getCode(),
                ]);
            };
        }

        return view("dashboard.index", ([
            'banner' => $banner_live,
            'url_request_live' => $url_request_live,
            'on_boarding' => $on_boarding,
            'cache' => $on_boarding_cache,
            'env_app' => $env_app
        ]));
    }

    public function onPageHandling(){
        $on_boarding_cache = Cache::get('on_boarding_pages');

        if ($on_boarding_cache == 1){
            Cache::put('on_boarding_pages',2);
        }
    }
}
