<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImsUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ims_user_roles')->insert([
            [
                'emp_id' => 1,
                'role_id' => 2, // Employee Role
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'emp_id' => 2,
                'role_id' => 2, // Employee Role
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'emp_id' => 3,
                'role_id' => 2, // Employee Role
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'emp_id' => 4,
                'role_id' => 2, // Employee Role
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
