<?php

namespace Database\Seeders\Batch2;

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
