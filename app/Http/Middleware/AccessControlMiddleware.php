<?php

namespace App\Http\Middleware;

use App\Helpers\AccessControlHelper;
use Closure;
use Illuminate\Http\Request;

class AccessControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$args)
    {
        if (AccessControlHelper::checkCurrentAccessControl($args, "AND")) {
            return $next($request);
        } else {
            // TODO: Custom 401 page?
            $access_control = "";
            if (! empty($args)) {
                $access_control = implode(", ", $args);
            }
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}
