<?php

namespace Database\Seeders\Batch20;

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
        $parent = SidebarMenuItem::query()
            ->where('title', 'Money Transfer')
            ->where('target_type', 'YUKK_CO')
            ->where('type', 'SUBMENU')->first();

        SidebarMenuItem::updateOrCreate([
            'title' => 'Batch Group',
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
        ], [
            'icon_class' => 'icon-list',
            'route' => '/money_transfer/transactions/batches',
            'parent_id' => $parent->id,
            'route_type' => 'FULL_URL',
            'access_control' => [
                'MONEY_TRANSFER.TRANSACTION_BATCHES.VIEW',
            ],
            'access_control_type' => 'AND',
        ]);

        SidebarMenuItem::updateOrCreate([
            'title' => 'Provider Group',
            'target_type' => 'YUKK_CO',
            'type' => 'MENU',
        ], [
            'icon_class' => 'icon-archive',
            'parent_id' => $parent->id,
            'route' => '/money_transfer/transactions/groups',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'MONEY_TRANSFER.TRANSACTION_GROUPS.VIEW',
            ],
            'access_control_type' => 'AND',
        ]);
    }
}

