<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 29-Jul-21
 * Time: 15:41
 */

namespace App\Helpers;


class S {

    public static function isLogin() {
        return session()->has("user_role") && session()->has("jwt_token") && session()->has("user") && session()->has("user_role_list");
    }
    public static function getUser() {
        return session()->get("user", null);
    }
    public static function getUserRole() {
        return session()->get("user_role", null);
    }
    public static function getUserRoleList() {
        $user_role_list = session()->get("user_role_list", []);
        if (! $user_role_list) {
            $user_role_list = [];
        } else if (! is_array($user_role_list)) {
            $user_role_list = [$user_role_list];
        }
        return $user_role_list;
    }
    public static function getJwtToken() {
        return session()->get("jwt_token", null);
    }

    /**
     * Short hand to get attribute of Session
     */
    public static function getUserName() {
        $user = S::getUser();
        return $user && $user->full_name ? $user->full_name : "";
    }
    public static function getTargetName() {
        $user_role = S::getUserRole();
        return $user_role && $user_role->target_name ? $user_role->target_name : "";
    }
    public static function getRoleName() {
        $user_role = S::getUserRole();
        return $user_role && $user_role->role && $user_role->role->name ? $user_role->role->name : "";
    }
    public static function getTargetType() {
        $user_role = S::getUserRole();
        return $user_role && $user_role->target_type ? $user_role->target_type : "";
    }
    public static function getTargetId() {
        $user_role = S::getUserRole();
        return $user_role && $user_role->target_id ? $user_role->target_id : "";
    }
    public static function getUserSetting($name = null) {
        $user = S::getUser();

        $settings = data_get($user, 'settings');

        if (empty($name)) return $settings;

        $result = collect($settings)->filter(function($setting) use ($name) {
            return $setting->name == $name;
        })->first();

        return $result;
    }



    // Flash Message Related
    public static function flashSuccess($message, $is_global = false) {
        session()->flash($is_global ? "global_success_message" : "success_message", $message);
    }
    public static function getFlashSuccess($is_global = false) {
        return session()->get($is_global ? "global_success_message" : "success_message", null);
    }

    public static function flashFailed($message, $is_global = false) {
        session()->flash($is_global ? "global_failed_message" : "failed_message", $message);
    }
    public static function getFlashFailed($is_global = false) {
        return session()->get($is_global ? "global_failed_message" : "failed_message", null);
    }



    public static function getThrottleSession() {
        $throttle_session = session("throttle_session", null);
        if ($throttle_session == null) {
            $throttle_session = H::generateRandomString();
            session(["throttle_session" => $throttle_session]);
        }
        return $throttle_session;

    }
}