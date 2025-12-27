<?php

namespace App\Http\Middleware;

use App\Helpers\H;
use Closure;
use Illuminate\Http\Request;

class MustLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*$active_user_role = $request->session()->get("user_role", null);

        if ($active_user_role) {
            $request->user_role = $active_user_role;
            return $next($request);
        } else {
            // Redirect to the Login Page or something like that
            dd("Redirect to Login Page");
        }*/

        if (H::isLogin()) {
            $request->user_role = session()->get("user_role");
            return $next($request);
        } else {
            return redirect(route("cms.login"))->with(["status_message" => trans("cms.Session Expired. Please login again to continue.")]);
        }

    }
}
