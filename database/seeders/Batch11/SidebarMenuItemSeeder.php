<?php

namespace Database\Seeders\Batch11;

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
        $menu = SidebarMenuItem::updateOrCreate([
            'title' => 'Topup Validation',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-server',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ["LAST_TOPUP_VALIDATION.VIEW"],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Suspect List',
            'parent_id' => $menu->id
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-profile',
            'route' => '/yukk_co/manage_suspects',
            'route_type' => 'FULL_URL',
            'access_control' => ["LAST_TOPUP_VALIDATION.VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Settings',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-office',
            'route' => '/yukk_co/manage_settings',
            'route_type' => 'FULL_URL',
            'access_control' => ["SETTINGS.VIEW"],
            'access_control_type' => 'AND',
        ]);
    }
}
