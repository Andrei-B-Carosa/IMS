<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImsSystemFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ims_system_files')->insert([
            [
                'name' => 'Dashboard',
                'href' => 'dashboard',
                'icon' => null,
                'folder' => null,
                'is_layered' =>0,
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inventory',
                'href' => 'inventory',
                'icon' => null,
                'folder' => 'inventory',
                'is_layered' => 0,
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accountability',
                'href' => 'accountability',
                'icon' => null,
                'is_layered' => 0,
                'folder' => 'accountability',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Material Issuance',
                'href' => 'material_issuance',
                'icon' => null,
                'is_layered' => 0,
                'folder' => 'material_issuance',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Settings',
                'href' => 'settings',
                'icon' => null,
                'is_layered' => 1,
                'folder' => 'settings',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reports',
                'href' => 'reports',
                'icon' => null,
                'is_layered' => 1,
                'folder' => 'reports',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
