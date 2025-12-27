<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Helpers\H;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    protected $pageOptions = [10, 20, 30, 40, 50, 100];
    
    public function getErrorAction($logTitle, $response)
    {
        Log::error($logTitle, [
            'responses' => [
                'response' => $response,
                'hit_status_code' => $response->status(),
                'response_status_code' => $response->json('status_code'),
                'message' => $response->json('status_message') ?? null,
            ]
        ]);

        if($response->json('status_code') == 4011) {
            $this->logout();
        }

        abort_if($response->status() == 404, 404);
        
        H::flashFailed(__('There is something wrong with our server. Please try again later.'), true);
        return redirect(route("cms.dashboard"));
    }

    public function getValidConfigValue(array $setting)
    {
        if($setting['type'] == 'array') {
            return array_filter(explode(',', $setting['value']), fn ($val) => ! empty($val));
        } 
        // else if($type == 'integer' || $type == 'float') {
        //     return $settings[$name] ?? 0;
        // } else if($type == 'string') {
        //     return $settings[$name] ?? "";
        // } 
        else {
            return $setting['value'];
        }
    }

    public function logout()
    {
        session()->remove("user_role");
        session()->remove("jwt_token");
        session()->remove("user");
        session()->remove("user_role_list");
        session()->remove("throttle_session");
        session()->regenerate(true);
        session()->regenerateToken();

        H::flashFailed(__('Session Expired. Please login again to continue.'), true);
        return redirect(route("cms.login"));
    }
}
