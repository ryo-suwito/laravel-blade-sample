<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 03-Aug-21
 * Time: 23:26
 */

namespace App\Helpers;


class AccessControlHelper {

    public static function checkCurrentAccessControl($check_access_control, $check_condition = "AND") {
        $user_role = S::getUserRole();
        if ($user_role) {
            $role = isset($user_role->role) ? $user_role->role : null;

            if ($role) {
                $access_control_list = isset($role->access_control) ? $role->access_control : [];
                if (is_string($access_control_list)) {
                    $access_control_list = json_decode($access_control_list);
                }
                if (is_array($access_control_list)) {
                    if (! is_array($check_access_control)) {
                        $check_access_control = [$check_access_control];
                    }

                    foreach ($check_access_control as $index => $needed_access_control) {
                        if (in_array($needed_access_control, $access_control_list)) {
                            if ($check_condition == "OR") {
                                return true;
                            }
                        } else {
                            if ($check_condition == "AND") {
                                return false;
                            }
                        }
                    }

                    if ($check_condition == "OR") {
                        // OR and reach here, that means there is no Access Control Match
                        return false;
                    } else {
                        // Default is AND
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
