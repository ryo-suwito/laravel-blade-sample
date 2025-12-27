<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 09-Aug-21
 * Time: 12:05
 */

namespace App\Http\Controllers;


use App\Helpers\ApiHelper;
use App\Helpers\CustomResponse;
use App\Helpers\EmailHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Jobs\SendEmailForgotPasswordJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController {

    public function index(Request $request) {
        if (S::isLogin()) {
            // Already logged in, redirect to Index
            return redirect(route("cms.index"));
        } else {
            // Login
            return view("forgot_password.index");
        }
    }

    public function process(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
        ]);
        $validator->validate();

        $email = $request->get("email");

        // Send to Store Management first to get the Forgot Password Token.
        $custom_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_STORE_FORGOT_PASSWORD_REQUEST_TOKEN, [
            "form_params" => [
                "email" => $email,
            ],
        ]);

        if ($custom_response->is_ok) {
            // Store Management return okay.

            //$result = EmailHelper::sendEmail($email, null, "Test Forgot Password", "<h1>Test Forgot Password</h1><br><p>$email</p>");
            if (isset($custom_response->result->token) && isset($custom_response->result->email)) {
                $token_ = $custom_response->result->token;
                $email_ = $custom_response->result->email;

                //dd([$token_, $email_]);
                // Send Email?
                dispatch(new SendEmailForgotPasswordJob($email_, $token_));

                return redirect(route("cms.forgot_password.complete_request"));
            } else {
                // Token and/or email not found?
                // return error meybe?

                return back()->withInput()->withErrors(__("cms.There is something wrong with our server. Please try again later."));
            }
        } else if ($custom_response->status_code == CustomResponse::STATUS_CODE_ITEM_NOT_FOUND) {
            //dd("email_not_found");
            // Email not Found.
            // Either send to complete or tell the user that their email is not found.

            // Option 1:
            //  Pretend that we already send email
            //return redirect(route("cms.forgot_password.complete_request"));

            // Option 1:
            //  Tell user that their email address is not found
            return back()->withInput()->withErrors(__("cms.Your email is not detected in our database. Please check your email address and try again."));
        } else {
            // return error maybe?
            return back()->withInput()->withErrors(__("cms.There is something wrong with our server. Please try again later."));
        }
    }

    public function complete(Request $request) {
        return view("forgot_password.complete_request");
    }


    // Attempt forgot password by Token and Email Given.
    public function attempt(Request $request) {
        $email = $request->get("email", null);
        $token = $request->get("token", null);

        if ($email && $token) {
            $custom_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_STORE_FORGOT_PASSWORD_CHECK_TOKEN, [
                "form_params" => [
                    "email" => $email,
                    "token" => $token,
                ],
            ]);

            if ($custom_response->is_ok) {
                // Email/Token pair Found
                return view("forgot_password.password_reset", [
                    "email" => $email,
                    "token" => $token,
                ]);
            } else if ($custom_response->status_code == CustomResponse::STATUS_CODE_ITEM_NOT_FOUND) {
                // API response that the email/token pair is not found
                return abort(404);
            } else {
                // Error other
                return abort(404);
            }
        } else {
            // Email and Token is not provided
            return abort(404);
        }
    }

    // Reset password with given new password
    public function reset(Request $request) {
        $validator = Validator::make($request->all(), [
            "new_password" => "required|confirmed",
        ]);
        $validator->validate();

        $new_password = $request->get("new_password", null);

        $email = $request->get("email", null);
        $token = $request->get("token", null);

        if ($email && $token) {
            $custom_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_STORE_FORGOT_PASSWORD_CHANGE_PASSWORD, [
                "form_params" => [
                    "email" => $email,
                    "token" => $token,
                    "new_password" => $new_password,
                ],
            ]);

            if ($custom_response->is_ok) {
                // Email/Token pair Found
                return view("forgot_password.success_change_password", []);
            } else if ($custom_response->status_code == CustomResponse::STATUS_CODE_ITEM_NOT_FOUND) {
                // API response that the email/token pair is not found
                return abort(404);
            } else {
                // Error other
                return view("forgot_password.failed_change_password", [
                    "failed_message" => $custom_response->status_message,
                    "email" => $email,
                    "token" => $token,
                ]);
            }
        } else {
            // Email and Token is not provided
            return abort(404);
        }
    }

}
