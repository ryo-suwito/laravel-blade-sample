<?php

namespace Database\Seeders\Batch4;

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
            'title' => 'Beneficiary MDR'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'icon_class' => 'icon-users',
            'route' => '/yukk_co/beneficiary_mdr',
            'route_type' => 'FULL_URL',
            'access_control' => ["BENEFICIARY_MDR.VIEW"],
            'access_control_type' => 'OR',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'PG Credentials'
        ], [
            'type' => 'MENU',
            'target_type' => 'PARTNER',
            'icon_class' => 'icon-file-text ',
            'route' => '/partner/credentials',
            'route_type' => 'FULL_URL',
            'access_control' => ["PAYMENT_GATEWAY.CREDENTIALS.VIEW"],
            'access_control_type' => 'AND',
            'sort_number' => 120.00,
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'PG Tech Docs'
        ], [
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
            'icon_class' => 'icon-file-text ',
            'route' => '/yukk_co/pg/tech-docs',
            'route_type' => 'FULL_URL',
            'access_control' => ["PAYMENT_GATEWAY.TECH_DOCS.VIEW", "PAYMENT_GATEWAY.TECH_DOCS.CREATE"],
            'access_control_type' => 'AND',
            'sort_number' => 120.00,
        ]);
    }
}
