<?php

namespace App\Actions\OTP;

use App\Services\OTP\SendOtpRequest;
use Illuminate\Http\Client\RequestException;
use Throwable;

class SendOtp
{
    /**
     * @param int $userId
     * @param string $email
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Throwable
     */
    public function __invoke(int $userId, string $email)
    {
        try {
            return api('otp')->send(new SendOtpRequest([
                'user_id' => $userId,
                'target' => $email,
            ]));
        } catch (RequestException $e) {
            return $e->response;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
