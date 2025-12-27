<?php

namespace Database\Seeders\Batch15;

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
            'title' => 'Activity Log',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-books',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => [
                "ACTIVITY_LOG",
            ],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'List Log',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/activity/logs',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-list2',
            'route_type' => 'FULL_URL',
            'access_control' => ["ACTIVITY_LOG"],
            'access_control_type' => 'AND',
        ]);
    }
}
