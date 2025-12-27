<?php

namespace App\Actions\OTP;

class UseOtpVerification
{
    /**
     * @return bool
     */
    public function __invoke(string $mail)
    {
        return config('otp.verification.enabled') == 1
            || in_array($mail, config('otp.verification.users'));
    }
}
