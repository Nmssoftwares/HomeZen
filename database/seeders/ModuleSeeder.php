<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modules')->insert([
            [
                'name'          => 'User Management',
                'short_name'    => 'UM',
                'group_name'    => 'System & Access Control',
                'group_order'   => 1,
                'description'   => 'Create and manage admin, sub-admins, and family users.',
                'icon'          => 'bi bi-people-fill',
                'dashboard_url' => 'um/dashboard',
                'status'        => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
