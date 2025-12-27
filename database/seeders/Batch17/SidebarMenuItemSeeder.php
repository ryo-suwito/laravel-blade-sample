<?php

namespace Database\Seeders\Batch17;

use App\Actions\Seeders\CheckSidebarMenuItem;
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
        $transactions = SidebarMenuItem::query()
            ->where('title', 'Transactions')
            ->where('type', 'SUBMENU')
            ->first();

        $criteria = [
            'title' => 'Transaction Merchant Online',
            'parent_id' => $transactions->id,
            'route' => '/yukk_co/transaction_merchant_online',
        ];

        (new CheckSidebarMenuItem($this->command, $criteria))->handle();

        SidebarMenuItem::updateOrCreate($criteria, [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-lanyrd',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'CORE_API.TRANSACTION_ONLINE.VIEW',
            ],
            'access_control_type' => 'AND',
        ]);

        $partner_menu = SidebarMenuItem::where('title', 'Partner Menu')
            ->where('type', 'SUBMENU')
            ->first();

        $criteria = [
            'title' => 'Platform Settings',
            'parent_id' => $partner_menu->id,
            'route' => '/yukk_co/platform_setting',
        ];

        (new CheckSidebarMenuItem($this->command, $criteria))->handle();

        SidebarMenuItem::updateOrCreate($criteria, [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-list2',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'CORE_API.PLATFORM_SETTINGS.VIEW',
                'CORE_API.PLATFORM_SETTINGS.UPDATE',
                'CORE_API.PLATFORM_SETTINGS.CREATE',
            ],
            'access_control_type' => 'OR',
        ]);
    }
}

