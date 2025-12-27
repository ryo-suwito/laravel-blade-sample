<?php

namespace App\Http\Middleware;

use App\Actions\OTP\GetOtpSession;
use Closure;
use Illuminate\Http\Request;

class NeedVerifyOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $session = new GetOtpSession(session()->get('user')->id);

        if ($session->isVerified()) {
            return redirect()->route('cms.dashboard');
        }

        return $next($request);
    }
}
