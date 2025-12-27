<?php

namespace Database\Seeders\Batch5;

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

        $submenu = SidebarMenuItem::updateOrCreate([
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
                "MONEY_TRANSFER.PARTNER_SETTINGS_VIEW"
            ],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Top Up Balance'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'parent_id' => $submenu->id,
            'icon_class' => 'icon-folder-plus',
            'route' => '/money_transfer/provider_deposits',
            'route_type' => 'FULL_URL',
            'access_control' => ["MONEY_TRANSFER.TOP_UP_BALANCE_VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Transaksi List'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'parent_id' => $submenu->id,
            'icon_class' => 'icon-file-text',
            'route' => '/money_transfer/transactions',
            'route_type' => 'FULL_URL',
            'access_control' => ["MONEY_TRANSFER.TRANSACTIONS_VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Rekon Provider Balance'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'parent_id' => $submenu->id,
            'icon_class' => 'icon-history',
            'route' => '/money_transfer/provider_balance_histories',
            'route_type' => 'FULL_URL',
            'access_control' => ["MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_VIEW"],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Manage Partner'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'parent_id' => $submenu->id,
            'icon_class' => 'icon-user-tie',
            'route' => '/money_transfer/partners',
            'route_type' => 'FULL_URL',
            'access_control' => ["MONEY_TRANSFER.PARTNER_SETTINGS_VIEW"],
            'access_control_type' => 'AND',
        ]);
        
    }
}
