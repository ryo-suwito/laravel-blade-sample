<?php

namespace Database\Seeders\Batch16;

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
        $criteria = [
            'title' => 'Client Credential',
            'type' => 'MENU',
            'target_type' => 'YUKK_CO',
        ];

        (new CheckSidebarMenuItem($this->command, $criteria))->handle();

        SidebarMenuItem::updateOrCreate($criteria, [
            'icon_class' => 'icon-user-lock',
            'route' => '/client_credentials',
            'route_type' => 'FULL_URL',
            'access_control' => [
                'CLIENT_MANAGEMENT.CLIENTS.VIEW',
                'CLIENT_MANAGEMENT.CLIENTS.CREATE',
                'CLIENT_MANAGEMENT.CLIENTS.UPDATE',
            ],
            'access_control_type' => 'OR',
        ]);
    }
}
