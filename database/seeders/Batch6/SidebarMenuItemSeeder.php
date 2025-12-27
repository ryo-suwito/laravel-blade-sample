<?php

namespace Database\Seeders\Batch6;

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
        ], [
            'target_type' => 'PARTNER',
            'icon_class' => 'icon-coin-dollar',
            'route' => '/money_transfer/redirect',
            'route_type' => 'FULL_URL',
            'access_control' => [
                "MONEY_TRANSFER.TRANSFER",
                "MONEY_TRANSFER.YUKK_CASH",
            ],
            'access_control_type' => 'OR',
        ]);
    }
}
