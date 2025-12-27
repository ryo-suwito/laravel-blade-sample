<?php

namespace Database\Seeders\Batch20;

use Database\Seeders\Batch20\SidebarMenuItemSeeder;
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
