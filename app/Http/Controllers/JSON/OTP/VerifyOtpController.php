<?php

namespace App\Http\Controllers\JSON\OTP;

use App\Actions\OTP\GetOtpSession;
use App\Actions\OTP\VerifyOtp;
use App\Helpers\H;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, VerifyOtp $verifyOtp)
    {
        $user = $request->session()->get('user');

        $session = new GetOtpSession($user->id);

        $response = $verifyOtp(
            $user->id,
            $user->username,
            $request->get('otp_value'),
            $rememberToken = $request->input('remember_me') == 'on'
                ? Str::random(64)
                : null
        );

        if ($response->status() == 429) {
            $session->setEnableInput(false);

            return response()->json([
                'message' => $response->json('message')
            ], $response->status());
        }

        if (! $response->successful()) {
            return response()->json([
                'message' => Response::$statusTexts[$response->status()],
            ], $response->status());
        }

        $session->clear();
        $session->verified();

        Cookie::queue('remember_token', $rememberToken, 60 * 24 * 30);

        H::flashSuccess('You have logged in successfully.', true);

        return response()->json($response->json());
    }
}
