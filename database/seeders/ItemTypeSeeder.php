<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ims_item_types')->insert([
            // [
            //     'name' => 'System Unit',
            //     'description' => '',
            //     'is_active' => 1,
            //     'display_to' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Printer',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Keyboard',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Mouse',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Monitor',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Speaker',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Webcam',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Laptop',
            //     'description' => '',
            //     'display_to' => 1,
            //     'is_active' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            [
                'name' => 'Ink',
                'description' => 'Ink',
                'display_to' => 2,
                'is_active' => 1,
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
