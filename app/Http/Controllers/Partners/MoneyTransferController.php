<?php

namespace App\Http\Controllers\Partners;

use App\Helpers\ApiHelper;
use App\Helpers\S;
use App\Http\Controllers\Controller;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class MoneyTransferController extends Controller
{
    public function redirect(Request $request) {
        $userId = S::getUser()->id;

        $key = base64_decode(
            Str::after(config('money_transfer.shared_key'), 'base64:')
        );

        $encrypter = new Encrypter(
            $key,
            config('money_transfer.cipher')
        );

        $token = $encrypter->encrypt([
            'user' => [
                'id' => $userId
            ],
            'expires_at' => now()->addSeconds(config('money_transfer.login.expires_in'))->timestamp,
            'options' => [
                'route' => [
                    'to' => $request->get("route"),
                ],
            ],
        ]);

        $path = str_replace(':token', $token, config('money_transfer.login.path'));

        return Redirect::away(
            config('money_transfer.url') . '/' . $path
        );
    }

    public function balance(Request $request) {
        $money_transfer_response = ApiHelper::requestGeneral("GET", "money-transfer/partner/balance", []);

        if ($money_transfer_response->status_code != 200) {
            return view("global.default_api_response_not_ok", ["custom_response" => $money_transfer_response]);
        }

        return view("partners.money_transfers.balance", [
            "balance" => $money_transfer_response->result,
        ]);
    }
}
