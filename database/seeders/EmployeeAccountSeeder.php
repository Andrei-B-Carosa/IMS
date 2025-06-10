<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employee_accounts')->insert([
            [
            'emp_id' => 1,
            'c_email' => 'andrei.carosa@rvlmovers.com',
            'username' => 'andrei.carosa',
            'password' => Hash::make('Ims@2025'),
            'bypass_key' => Crypt::encrypt('Ims@2025'),
            'is_active' => 1,
            'is_deleted' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_by' => 1,
            'created_at' => now(),
            ],
            [
            'emp_id' => 2,
            'c_email' => 'tam.dolormente@rvlmovers.com',
            'username' => 'tam.dolormente',
            'password' => Hash::make('Ims@2025'),
            'bypass_key' => Crypt::encrypt('Ims@2025'),
            'is_active' => 1,
            'is_deleted' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_by' => 1,
            'created_at' => now(),
            ],
            [
            'emp_id' => 3,
            'c_email' => 'cedrick.delarosa@rvlmovers.com',
            'username' => 'cedrick.delarosa',
            'password' => Hash::make('Ims@2025'),
            'bypass_key' => Crypt::encrypt('Ims@2025'),
            'is_active' => 1,
            'is_deleted' => null,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_by' => 1,
            'created_at' => now(),
            ],
            [
            'emp_id' => 4,
            'c_email' => 'marwin.reyes@rvlmovers.com',
            'username' => 'marwin.reyes',
            'password' => Hash::make('Ims@2025'),
            'bypass_key' => Crypt::encrypt('Ims@2025'),
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
