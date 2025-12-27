<?php

namespace Database\Seeders\Batch10;

use App\Models\SidebarMenuItem;
use Illuminate\Database\Seeder;

class SidebarMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuDTTOT = SidebarMenuItem::updateOrCreate([
            'title' => 'DTTOT',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-profile',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["DTTOT.VIEW"],
            'access_control_type' => 'OR',
        ]);

        $menuSuspected = SidebarMenuItem::updateOrCreate([
            'title' => 'Suspected User',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-user-tie',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["SUSPECTED_USERS.VIEW"],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'DTTOT List',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-profile',
            'parent_id' => $menuDTTOT->id,
            'route' => '/yukk_co/dttot/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["DTTOT.VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'DTTOT Approval',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-checkmark',
            'parent_id' => $menuDTTOT->id,
            'route' => '/yukk_co/dttot/approval/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["DTTOT_APPROVAL.VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Suspected User List',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-user-tie',
            'parent_id' => $menuSuspected->id,
            'route' => '/yukk_co/suspected_user/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["SUSPECTED_USERS.VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Suspected User Approval',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-checkmark',
            'parent_id' => $menuSuspected->id,
            'route' => '/yukk_co/suspected_user/approval/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["SUSPECTED_USERS_APPROVAL.VIEW"],
            'access_control_type' => 'AND',
        ]);
    }
}
