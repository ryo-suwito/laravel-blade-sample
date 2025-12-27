<?php

namespace Database\Seeders\Batch12;

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
            'title' => 'Settlement Online',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-cart',
            'parent_id' => 3,
            'route' => '/yukk-co/transaction-online/settlements',
            'route_type' => 'FULL_URL',
            'access_control' => ["SETTLEMENT.SETTLEMENT_ONLINE"],
            'access_control_type' => 'AND',
        ]);

    }
}
