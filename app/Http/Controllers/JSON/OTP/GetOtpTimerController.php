<?php

namespace App\Http\Controllers\JSON\OTP;

use App\Actions\OTP\GetOtpSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetOtpTimerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->session()->get('user');

        $session = new GetOtpSession($user->id);

        return response()->json([
            'expired_at' => $session->getExpiredAt() ?? now(),
            'resend_count' => $session->getSentCount(),
            'disable_input' => ! $session->isEnabledInput(),
        ]);
    }
}
