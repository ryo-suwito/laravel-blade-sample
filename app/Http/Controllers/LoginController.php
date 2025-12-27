<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Jul-21
 * Time: 14:07
 */

namespace App\Http\Controllers;

use App\Actions\Login\RedirectToDashboard;
use App\Actions\OTP\GetOtpSession;
use App\Actions\OTP\SendOtp;
use App\Actions\OTP\UseOtpVerification;
use App\Actions\OTP\VerifyOtpSession;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Services\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends BaseController
{
    public function index(Request $request) {
        if (H::isLogin() && $request->session()->get('Verified') === true) {
            // Already logged in, redirect to Index
            return redirect(route("cms.dashboard"));
        } else {
            // Login
            return view("login.index");
        }
    }

    public function login(
        Request $request,
        UseOtpVerification $useOtpVerification,
        VerifyOtpSession $verifyOtpSession,
        SendOtp $sendOtp
    )
    {
        if (RateLimiter::tooManyAttempts('cms-login:' . S::getThrottleSession(), $max_attempt = 3)) {
            return back()->withErrors(["status_message" => trans("cms.login_throttle_error")])->withInput();
        }

        Validator::make($request->all(), [
            "username" => "required|email",
            "password" => "required",
        ])->validate();

        $username = $request->get("username", null);
        $password = $request->get("password", null);

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_STORE_AUTH_LOGIN, [
            "form_params" => [
                "username" => $username,
                "password" => $password,
            ],
        ]);

        if ($response->is_ok ?? false) {
            $user = $response->result->user;
            $jwt_token = $response->result->jwt_token;
            $user_role_list = $response->result->user->user_role_list;
            unset($user->user_role_list);

            $primary_user_role = null;
            foreach ($user_role_list as $user_role) {
                if ($user_role->is_primary) {
                    $primary_user_role = $user_role;
                    break;
                }
            }

            if ($primary_user_role) {
                session()->put("user_role", $primary_user_role);
                session()->put("jwt_token", $jwt_token);
                session()->put("user", $user);
                session()->put("user_role_list", $user_role_list);

                $session = new GetOtpSession($user->id);

                if (! $useOtpVerification($user->username)
                    || $verifyOtpSession($user->id, $user->username, $request->cookie('remember_token'))
                ) {
                    $session->verified();

                    return (new RedirectToDashboard)();
                }

                if ($session->canSendOtp()) {
                    $response = $sendOtp($user->id, $user->username);

                    if ($response->successful()) {
                        $session->update($response->json('data.expired_at'));
                    }
                }

                return redirect()->route('otp.index');
            } else {
                return back()->withErrors(["status_message" => trans("cms.login_failed_user_role_not_found")])->withInput();
            }
        } else {
            RateLimiter::hit('cms-login:' . S::getThrottleSession());

            return back()->withErrors(["status_message" => $response->status_message])->withInput();
        }
    }

    public function logout(Request $request) {
        $service = API::instance('store_management', 'auth');

        $response = $service->logout([
            'username' => session()->get('user')->username
        ]);

        if ($response->status() == 200) {
            (new GetOtpSession(session()->get('user')->id))->clear();

            session()->remove("user_role");
            session()->remove("jwt_token");
            session()->remove("user");
            session()->remove("user_role_list");
            session()->remove("throttle_session");
            session()->regenerate(true);
            session()->regenerateToken();

            return redirect(route("cms.login"));
        }

        H::flashFailed("Something wrong!");
        return redirect('/');
    }

    public function env(Request $request, $token, $username) {
        $response = ApiHelper::requestGeneral("POST", 'store/auth/catch/environment', [
            "form_params" => [
                "username" => base64_decode($username),
            ],
        ]);

        if ($response->result == null) {
            H::flashFailed($response->status_message);
            return redirect(route("cms.login"));
        }

        $user = $response->result->user;


        $jwt_token = $response->result->jwt_token;
        $user_role_list = $response->result->user->user_role_list;
        unset($user->user_role_list);

        $primary_user_role = null;
        foreach ($user_role_list as $user_role) {
            if ($user_role->is_primary) {
                $primary_user_role = $user_role;
                break;
            }
        }

        if ($primary_user_role) {
            session()->put("user_role", $primary_user_role);
            session()->put("jwt_token", $jwt_token);
            session()->put("user", $user);
            session()->put("user_role_list", $user_role_list);

            if (config('app.mode') != 'production') {
                (new GetOtpSession($user->id))->verified();
            }

            H::flashSuccess(trans("cms.Login Success"));

            return redirect(route("cms.index"));
        } else {
            // ??? User Role not found?
            return redirect('/')->withErrors(["status_message" => trans("cms.login_failed_user_role_not_found")])->withInput();
        }
    }

}
