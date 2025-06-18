<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImsSystemFileLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ims_system_file_layers')->insert([
            // [
            //     'file_id' => 5,
            //     'layer_id' => 1,
            //     'status' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'file_id' => 5,
            //     'layer_id' => 2,
            //     'status' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'file_id' => 5,
            //     'layer_id' => 3,
            //     'status' => 1,
            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
                [
                    'file_id' => 6,
                    'layer_id' => 4,
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
                    'file_id' => 6,
                    'layer_id' => 5,
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
                    'file_id' => 6,
                    'layer_id' => 6,
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
                    'file_id' => 6,
                    'layer_id' => 7,
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
