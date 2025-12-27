<?php

namespace App\Http\Controllers;

use App\Actions\OTP\MaskingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use App\Helpers\H;

class OtpController extends BaseController
{
    protected $otp;

    public function __construct()
    {
        $this->otp = api('OTP', 'OTP');
        $this->getAccessToken = api('getAccessToken', 'getAccessToken');
    }

    public function index(Request $request)
    {
        $user = $request->session()->get('user');

        $email = (new MaskingEmail)($user->username);

        return view('otp.index', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $user = $request->session()->get('user');
        $otpValue = $request->input('otp_value');
        $rememberMe = $request->input('remember_me');
        $accessToken = $this->getAccessToken->getAccessToken();

        if ($rememberMe == 'on') {
            $remember_token = Str::random(64);
            Cookie::queue('remember_token', $remember_token, 60 * 24 * 30);

            $otpData = [
                'user_type' => 'STORE_USER',
                'user_id' => $user->id,
                'target' => $user->username,
                'action' => 'LOGIN',
                'source' => 'CMS_DASHBOARD',
                'token' => $otpValue,
                'remember' => [
                    'token' => $remember_token,
                ],
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ];

            $temp = $this->otp->verifyOtp($otpData, $accessToken['access_token']);

        } else {
            $otpData = [
                'user_id' => $user->id,
                'user_type' => 'STORE_USER',
                'target' => $user->username,
                'action' => 'LOGIN',
                'source' => 'CMS_DASHBOARD',
                'token' => $otpValue,
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ];

            $temp = $this->otp->verifyOtp($otpData, $accessToken['access_token']);
        }
            $status = $temp['status'];
        if ($status === 200 || $temp['body']['message'] === 'Successful.') {
            Cache::forget('resend_count_'.$user->id);
            Cache::forget('disable_input_'.$user->id);
            $request->session()->put('Verified', true);
            H::flashSuccess("You have logged in successfully.", true);
            return response()->json(['message' => $temp['body']['message']])->setStatusCode($status);
        } elseif ($temp['status'] === 429) {
            Cache::put('disable_input_' . $user->id, true, now()->addMinutes(60));
            return response()->json(['message' => $temp['body']['message']])->setStatusCode($status);
        } else {
            $request->session()->put('Verified', false);
            return response()->json(['message' => $temp['body']['message']])->setStatusCode($status);
        }
    }

    public function resendOtp(Request $request)
    {
        $user = $request->session()->get('user');
        $accessToken = $this->getAccessToken->getAccessToken();
        $resendCountKey = 'resend_count_' . $user->id;
        $resendCount = Cache::get($resendCountKey, 0);

        $otpData = [
            'user_id' => $user->id,
            'user_type' => 'STORE_USER',
            'target' => $user->username,
            'action' => 'LOGIN',
            'source' => 'CMS_DASHBOARD',
            'channel' => config('otp.channel'),
            'provider' => config('otp.provider'),
            'properties' => [
                'expires_in' => config('otp.expires_in')
            ],
        ];

        $temp = $this->otp->sendOtp($otpData, $accessToken['access_token']);
        $status = $temp['status'];

        if ($temp['status'] === 200 || $temp['body']['message'] === 'Successful.') {
            $resendCount++;
            Cache::put($resendCountKey, $resendCount, now()->addMinutes(60));
            Cache::put('expired_at_' . $user->id, $temp['body']['data']['expired_at'], now()->addMinutes(60));
            Cache::put('disable_input_' . $user->id, false, now()->addMinutes(60));
            return response()->json(['message' => $temp['body']['message'], 'expired_at' => $temp['body']['data']['expired_at'], 'resend_count' => $resendCount])->setStatusCode($status);
        } else {
            return response()->json(['message' => $temp['body']['message'], 'resend_count' => $resendCount])->setStatusCode($status);
        }
    }

    public function otpTimer(Request $request)
    {
        $user = $request->session()->get('user');
        $expired_at = Cache::get('expired_at_' . $user-> id);
        $resendCount = Cache::get('resend_count_'.$user->id, 0);
        $disableInput = Cache::get('disable_input_'.$user->id, false);

        if ($expired_at) {
            return response()->json(['expired_at' => $expired_at, 'resend_count' => $resendCount, 'disable_input' => $disableInput]);
        }
    }
}
