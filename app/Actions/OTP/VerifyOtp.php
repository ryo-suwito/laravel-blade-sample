<?php

namespace App\Actions\OTP;

use App\Services\OTP\VerifyOtpRequest;
use Illuminate\Http\Client\RequestException;
use Throwable;

class VerifyOtp
{
    /**
     * @param int $userId
     * @param string $email
     * @param string $token
     * @param null|string $name
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function __invoke(int $userId, string $email, string $token, ?string $rememberToken = null)
    {
        try {
            return api('otp')->send(new VerifyOtpRequest([
                'user_id' => $userId,
                'target' => $email,
                'token' => $token,
                'remember' => [
                    'token' => $rememberToken,
                ],
            ]));
        } catch (RequestException $e) {
            return $e->response;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
