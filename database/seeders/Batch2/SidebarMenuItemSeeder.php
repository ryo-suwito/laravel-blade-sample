<?php

namespace Database\Seeders\Batch2;

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
        $storeManagement = SidebarMenuItem::updateOrCreate([
            'title' => 'Store Management'
        ], [
            'type' => 'SUBMENU',
            'target_type' => 'YUKK_CO',
            'icon_class' => 'icon-users',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["STORE.USERS_VIEW","STORE.USERS_EDIT","STORE.USERS_CREATE","STORE.ROLES_CREATE","STORE.ROLES_VIEW","STORE.ROLES_EDIT"],
            'access_control_type' => 'OR',
            'sort_number' => 120.00,
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'User Management',
        ], [
            'parent_id' => $storeManagement->id,
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'icon_class' => 'icon-users',
            'route' => '/store/users',
            'route_type' => 'FULL_URL',
            'access_control' => ["STORE.USERS_VIEW"],
            'access_control_type' => 'AND',
            'sort_number' => 120.00
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Role Management'
        ],[
            'parent_id' => $storeManagement->id,
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'icon_class' => 'icon-users',
            'route' => '/store/roles',
            'route_type' => 'FULL_URL',
            'access_control' => ["STORE.ROLES_VIEW"],
            'access_control_type' => 'AND',
            'sort_number' => 120.00
        ]);
    }
}
