<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImsRoleAccess extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1=Admin 2=Employee
        DB::table('ims_role_accesses')->insert([
            [
                'role_id' => 2,
                'file_id' => 1,
                'is_active' => 1,
                'file_order' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'file_id' => 2,
                'is_active' => 1,
                'file_order' => 2,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'file_id' => 3,
                'is_active' => 1,
                'file_order' => 3,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'file_id' => 4,
                'is_active' => 1,
                'file_order' => 4,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'file_id' => 6,
                'is_active' => 1,
                'file_order' => 5,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'file_id' => 5,
                'is_active' => 1,
                'file_order' => 5,
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
