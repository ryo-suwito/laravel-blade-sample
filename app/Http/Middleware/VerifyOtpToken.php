<?php

namespace App\Http\Middleware;

use App\Actions\OTP\GetOtpSession;
use Closure;
use Illuminate\Http\Request;

class VerifyOtpToken
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
        $session = new GetOtpSession(session()->get('user')->id);

        if ($session->isVerified()) {
            return $next($request);
        }

        return redirect(route('cms.login'))
            ->with(['status_message' => trans('Unverified User. Please Try Again.')]);
    }
}
