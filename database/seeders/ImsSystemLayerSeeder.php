<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImsSystemLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ims_system_layers')->insert([
            [
                'name' => 'File Maintenance',
                'href' => 'file_maintenance',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User Management',
                'href' => 'user_management',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee List',
                'href' => 'employee_list',
                'folder' => 'employee_list',
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Issued Devices',
                'href' => 'issued_devices',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Available Inventory',
                'href' => 'available_inventory',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Devices per Site',
                'href' => 'devices_per_site',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Devices per Department',
                'href' => 'devices_per_department',
                'folder' => null,
                'status' => 1,
                'is_deleted' => null,
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
