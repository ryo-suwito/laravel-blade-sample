<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 05-Aug-21
 * Time: 11:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    public static $relation_list = [];

    public static function getRelationList() {
        $all_relation_list = array();

        foreach (static::$relation_list as $key => $value) {
            $all_relation_list[] = $key;

            if (count($value::getRelationList()) > 0) {
                foreach ($value::getRelationList() as $child_key => $child_value) {
                    $all_relation_list[] = $key . "." . $child_value;
                }
            }
        }

        return $all_relation_list;
    }

    public static function getDao() {
        return self::getDaoWithInactive()->where("active", true);
    }

    public static function getDaoWithInactive() {
        return static::with(static::getRelationList());
    }
}