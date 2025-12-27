<?php

namespace App\Actions\OTP;

use Illuminate\Support\Str;

class MaskingEmail
{
    public function __invoke(string $email)
    {
        $nickname = Str::before($email, '@');
        $domain = Str::after($email, '@');

        $maskingNickname = str_pad(
            substr($nickname, 0, strlen($nickname) <= 3 ? 1 : 3),
            strlen($nickname),
            '*',
            STR_PAD_RIGHT
        );

        return $maskingNickname . '@' . $domain;
    }
}
