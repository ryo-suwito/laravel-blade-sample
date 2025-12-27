<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 03-Aug-21
 * Time: 23:34
 */

namespace App\Helpers;


use App\Models\SidebarMenuItem;

class SidebarHelper {

    public static function getPartnerSidebarMenuList() {
        $sidebar_menu_list = SidebarMenuItem::getDao()
            ->where("target_type", "PARTNER")
            ->whereNull("parent_id")
            ->orderBy("sort_number")
            ->get();

        //dd($sidebar_menu_list);
        return $sidebar_menu_list;
    }
    public static function getMerchantBranchSidebarMenuList() {
        $sidebar_menu_list = SidebarMenuItem::getDao()
            ->where("target_type", "MERCHANT_BRANCH")
            ->whereNull("parent_id")
            ->orderBy("sort_number")
            ->get();

        //dd($sidebar_menu_list);
        return $sidebar_menu_list;
    }
    public static function getCustomerSidebarMenuList() {
        $sidebar_menu_list = SidebarMenuItem::getDao()
            ->where("target_type", "CUSTOMER")
            ->whereNull("parent_id")
            ->orderBy("sort_number")
            ->get();

        //dd($sidebar_menu_list);
        return $sidebar_menu_list;
    }
    public static function getYukkCoSidebarMenuList() {
        $sidebar_menu_list = SidebarMenuItem::getDao()
            ->where("target_type", "YUKK_CO")
            ->whereNull("parent_id")
            ->orderBy("sort_number")
            ->get();

        //dd($sidebar_menu_list);
        return $sidebar_menu_list;
    }

    public static function getSidebarMenuAll() {
        if (S::getUserRole()->target_type == "PARTNER") {
            return self::getPartnerSidebarMenuList();
        } else if (S::getUserRole()->target_type == "MERCHANT_BRANCH") {
            return self::getMerchantBranchSidebarMenuList();
        } else if (S::getUserRole()->target_type == "CUSTOMER") {
            return self::getCustomerSidebarMenuList();
        } else if (S::getUserRole()->target_type == "YUKK_CO") {
            return self::getYukkCoSidebarMenuList();
        }

        return [];
    }

    /**
     * Get Sidebar Menu List already Filtered by Access Control
     */
    public static function getSidebarMenuFiltered() {
        $menu_item_list = self::getSidebarMenuAll();
        for ($i = count($menu_item_list) - 1; $i >= 0; $i--) {
            if (strtoupper($menu_item_list[$i]->type) == SidebarMenuItem::TYPE_MENU) {
                if (! AccessControlHelper::checkCurrentAccessControl($menu_item_list[$i]->access_control, $menu_item_list[$i]->access_control_type)) {
                    unset($menu_item_list[$i]);
                }
            } else if (strtoupper($menu_item_list[$i]->type) == SidebarMenuItem::TYPE_SUBMENU) {
                for ($j = count($menu_item_list[$i]->sub_menu_items) - 1; $j >= 0; $j--) {
                    if (! AccessControlHelper::checkCurrentAccessControl($menu_item_list[$i]->sub_menu_items[$j]->access_control, $menu_item_list[$i]->sub_menu_items[$j]->access_control_type)) {
                        unset($menu_item_list[$i]->sub_menu_items[$j]);
                    }
                }
                if (count($menu_item_list[$i]->sub_menu_items) <= 0) {
                    unset($menu_item_list[$i]);
                }
            }
        }
        return $menu_item_list;
    }

}