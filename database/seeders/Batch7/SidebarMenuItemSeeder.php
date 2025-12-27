<?php

namespace Database\Seeders\Batch7;

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
            'title' => 'Money Transfer',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-coin-dollar',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => [
                "MONEY_TRANSFER.TOP_UP_BALANCE_VIEW",
                "MONEY_TRANSFER.TRANSACTIONS_VIEW",
                "MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW",
                "MONEY_TRANSFER.PARTNER_SETTINGS_VIEW",
                "MONEY_TRANSFER.PROVIDER_SETTINGS_VIEW",7
            ],
            'access_control_type' => 'OR',
        ]);
        
        SidebarMenuItem::updateOrCreate([
            'title' => 'Manage Provider'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'parent_id' => $menu->id,
            'icon_class' => 'icon-briefcase',
            'route' => '/money_transfer/providers',
            'route_type' => 'FULL_URL',
            'access_control' => ["MONEY_TRANSFER.PROVIDER_SETTINGS_VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'route' => '/money_transfer/transactions'
        ], [
            'title' => 'Transaction List'
        ]);
    }
}
