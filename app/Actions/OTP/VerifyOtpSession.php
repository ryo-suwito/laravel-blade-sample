<?php

namespace App\Actions\OTP;

use App\Services\OTP\VerifySessionRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class VerifyOtpSession
{
    /**
     * @param int $userId
     * @param string $email
     * @return bool
     */
    public function __invoke(int $userId, string $email, ?string $token = null)
    {
        try {
            if (! $token) {
                return false;
            }

            api('otp')->send(new VerifySessionRequest([
                'user_id' => $userId,
                'target' => $email,
                'token' => $token,
            ]));

            return true;
        } catch (RequestException $e) {
            if ($e->response->serverError()) {
                throw $e;
            }

            Log::warning($e->getMessage(), [
                'class' => __CLASS__,
            ]);

            return false;
        } catch (Throwable $e) {
            Log::error($e);

            return false;
        }
    }
}
