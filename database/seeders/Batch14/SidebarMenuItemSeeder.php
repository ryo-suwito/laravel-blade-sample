<?php

namespace Database\Seeders\Batch14;

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
        SidebarMenuItem::updateOrCreate([
            'title' => 'Money Transfer',
            'type' => 'MENU',
            'target_type' => 'CUSTOMER',
        ], [
            'icon_class' => 'icon-coin-dollar',
            'route' => '/money_transfer/redirect',
            'route_type' => 'FULL_URL',
            'access_control' => [
                "MONEY_TRANSFER.TRANSFER",
                "MONEY_TRANSFER.YUKK_CASH",
            ],
            'access_control_type' => 'OR',
        ]);

        $submenu = SidebarMenuItem::where('title', 'Money Transfer')
            ->where('target_type', 'YUKK_CO')
            ->where('type', 'SUBMENU')->first();

        SidebarMenuItem::where('title', 'Manage Partner')
            ->where('parent_id', $submenu->id)->update([
                'title' => 'Manage User'
            ]);
    }
}
