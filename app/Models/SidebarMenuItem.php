<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarMenuItem extends BaseModel {

    const TYPE_MENU = "MENU";
    const TYPE_SUBMENU = "SUBMENU";


    const ROUTE_TYPE_FULL_URL = "FULL_URL";
    const ROUTE_TYPE_ROUTE_NAME = "ROUTE_NAME";

    protected $fillable = [
        'title', 'type', 'target_type', 'parent_id', 'icon_class', 'route', 'route_type', 'access_control', 'access_control_type', 'sort_number', 'status', 'active'
    ];

    protected $casts = [
        "access_control" => "array",
    ];

    public function sub_menu_items() {
        return $this->hasMany(SidebarMenuItem::class, "parent_id")->where("active", true)->orderBy("sort_number", "asc");
    }
}
