<?php

namespace Database\Seeders\Batch9;

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
            'title' => 'Beneficiary',
            'route' => '/yukk_co/disbursement_customer/list',
            'parent_id' => 6,
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'sort_number' => '90',
            'icon_class' => 'icon-user-tie',
            'route_type' => 'FULL_URL',
            'access_control' => ["DISBURSEMENT_CUSTOMER_VIEW"],
            'access_control_type' => 'AND',
        ]);
    }
}
