<?php

namespace Database\Seeders\Batch3;

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
            'title' => 'Merchant Branch Payment Link'
        ], [
            'type' => 'MENU',
            'target_type' => 'MERCHANT_BRANCH',
            'icon_class' => 'icon-users',
            'route' => '/merchant_branch/payment_link/list',
            'route_type' => 'FULL_URL',
            'access_control' => ["PAYMENT_GATEWAY.PAYMENT_LINK.VIEW"],
            'access_control_type' => 'OR',
            'sort_number' => 120.00,
        ]);
    }
}
