<?php

namespace Database\Seeders\Batch19;

use Database\Seeders\Batch19\SidebarMenuItemSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SidebarMenuItemSeeder::class);
    }
}
