<?php

namespace Database\Seeders\Batch21;

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
    public function run() {
        $this->createOwnerMenu();
        $this->createApprovalOwnerMenu();
    }

    private function createOwnerMenu()
    {
        $parent = SidebarMenuItem::query()
            ->where('title', 'Master Data')
            ->where('target_type', 'YUKK_CO')
            ->where('type', 'SUBMENU')->first();

        SidebarMenuItem::updateOrCreate([
            'title' => 'Owner',
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'parent_id' => $parent->id,
        ], [
            'icon_class' => 'icon-user',
            'route' => '/yukk_co/manage_owner',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'MASTER_DATA.OWNERS.VIEW',
                'MASTER_DATA.OWNERS.EDIT',
            ],
            'access_control_type' => 'OR',
        ]);
    }

    private function createApprovalOwnerMenu()
    {
        $parent = SidebarMenuItem::query()
            ->where('title', 'Approval')
            ->where('target_type', 'YUKK_CO')
            ->where('type', 'SUBMENU')->first();

        SidebarMenuItem::updateOrCreate([
            'title' => 'Owner',
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
            'parent_id' => $parent->id,
        ], [
            'icon_class' => 'icon-user',
            'route' => '/yukk_co/approvals/owners',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'MASTER_DATA.OWNERS.VIEW',
                'MASTER_DATA.OWNERS.EDIT',
            ],
            'access_control_type' => 'OR',
        ]);
    }
}
