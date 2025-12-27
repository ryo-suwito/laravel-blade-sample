<?php

/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 29-Jul-21
 * Time: 16:19
 */

namespace App\Http\Controllers;


use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MyProfileController extends BaseController
{

    public function changeTarget(Request $request)
    {
        $user_role_list = S::getUserRoleList();
        return view("my_profile.change_target", ["user_role_list" => $user_role_list]);
    }

    public function changeUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_role_id" => [
                "required",
                Rule::in(collect(S::getUserRoleList())->pluck("id")),
            ],
        ]);
        if (!$validator->fails()) {
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_STORE_CHANGE_PRIMARY_USER_ROLE, [
                "form_params" => [
                    "user_role_id" => $request->get("user_role_id", null),
                ],
            ]);
            if ($response->is_ok) {
                $response_my_profile = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_STORE_MY_PROFILE, []);

                //dd($response_my_profile);
                // Login Success
                $_user = $response_my_profile->result;
                $_user_role_list = $response_my_profile->result->user_role_list;
                unset($_user->user_role_list);

                $primary_user_role = null;
                foreach ($_user_role_list as $_user_role) {
                    if ($_user_role->is_primary) {
                        $primary_user_role = $_user_role;
                        break;
                    }
                }
                if ($primary_user_role) {
                    session()->put("user_role", $primary_user_role);
                    session()->put("user", $_user);
                    session()->put("user_role_list", $_user_role_list);

                    //dump([$response->result, $user, $jwt_token, $user_role_list]);
                    //dd(session()->all());

                    H::flashSuccess(trans("cms.Change Role Success"), true);

                    return redirect(route("cms.dashboard"));
                } else {
                    // ??? User Role not found?
                    // Force Logout
                    session()->remove("user_role");
                    session()->remove("jwt_token");
                    session()->remove("user");
                    session()->remove("user_role_list");
                    session()->regenerate(true);
                    session()->regenerateToken();

                    return redirect(route("cms.index"));
                }
            } else {
                H::flashFailed(trans($response->status_message), true);
                return back();
            }
        } else {
            H::flashFailed(trans("cms.User Role ID not Found"), true);
            return back();
        }
    }
}
