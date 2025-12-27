<?php

namespace Database\Seeders\Batch18;

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
