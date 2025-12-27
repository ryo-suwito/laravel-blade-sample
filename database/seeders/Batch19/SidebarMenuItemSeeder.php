<?php

namespace Database\Seeders\Batch19;

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
        $parent = SidebarMenuItem::query()
            ->where('title', 'Money Transfer')
            ->where('target_type', 'YUKK_CO')
            ->where('type', 'SUBMENU')->first();

        SidebarMenuItem::updateOrCreate([
            'title' => 'Settings',
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'parent_id' => $parent->id,
        ], [
            'icon_class' => 'icon-gear',
            'route' => '/money_transfer/settings',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'MONEY_TRANSFER.SETTINGS_VIEW',
            ],
            'access_control_type' => 'AND',
        ]);
    }
}

