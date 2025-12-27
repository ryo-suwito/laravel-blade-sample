<?php

namespace App\Http\Middleware;

use App\Actions\OTP\GetOtpSession;
use App\Actions\OTP\UseOtpVerification;
use Closure;
use Illuminate\Http\Request;

class CheckOtpConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = session()->get('user');

        if ((new UseOtpVerification)($user->username)) {
            return $next($request);
        }

        (new GetOtpSession($user->id))->verified();

        return redirect()->route('cms.dashboard');
    }
}
