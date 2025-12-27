<?php

namespace App\Http\Controllers\JSON\OTP;

use App\Actions\OTP\GetOtpSession;
use App\Actions\OTP\SendOtp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SendOtpController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, SendOtp $sendOtp)
    {
        $user = $request->session()->get('user');

        $session = new GetOtpSession($user->id);

        if (! $session->canSendOtp()) {
            return response()->json([
                'message' => Response::$statusTexts[429],
                'resend_count' => $session->getSentCount(),
            ], 429);
        }

        $response = $sendOtp($user->id, $user->username);

        if ($response->status() == 429) {
            $session->setEnableInput(false);

            return response()->json($response->json(), $response->status());
        }

        if (! $response->successful()) {
            return response()->json([
                'message' => Response::$statusTexts[$response->status()],
            ], $response->status());
        }

        $session->update($response->json('data.expired_at'));

        return response()->json([
            'message' => $response->json('message'),
            'expired_at' => $session->getExpiredAt(),
            'resend_count' => $session->getSentCount(),
        ]);
    }
}
