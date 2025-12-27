<?php

namespace Database\Seeders\Batch18;

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
        $menu = SidebarMenuItem::updateOrCreate([
            'title' => 'Legal Approval',
            'target_type' => 'YUKK_CO',
            'type' => 'SUBMENU',
        ], [
            'icon_class' => 'icon-books',
            'route' => '#',
            'route_type' => 'FULL_URL',
            'access_control' => ['LEGAL_APPROVAL.COMPANIES'],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Companies',
            'parent_id' => $menu->id,
            'route' => '/yukk_co/legal_approval/companies',
        ], [
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'icon_class' => 'icon-list2',
            'route_type' => 'FULL_URL',
            'access_control' => ['LEGAL_APPROVAL.COMPANIES'],
            'access_control_type' => 'AND',
        ]);
    }
}

