<?php

namespace Database\Seeders\Batch7;

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
