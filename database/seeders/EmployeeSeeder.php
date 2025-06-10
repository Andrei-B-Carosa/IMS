<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            [
            'emp_image' =>  null,
            'emp_no' => '23091331',

            'fname' => 'Andrei',
            'mname' => 'Bilan',
            'lname' => 'Carosa',
            'ext' => null,

            'is_active' => 1,
            'is_completed' => 1,

            'created_by' => 1,
            'created_at' => now(),
            ],
            [
                'emp_image' =>  null,
                'emp_no' => '24071430',

                'fname' => 'Tam',
                'mname' => 'Marcellana',
                'lname' => 'Dolormente',
                'ext' => null,

                'is_active' => 1,
                'is_completed' => 1,

                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'emp_image' =>  null,
                'emp_no' => '25041488',

                'fname' => 'John Cedrick',
                'mname' => null,
                'lname' => 'Dela Rosa',
                'ext' => null,

                'is_active' => 1,
                'is_completed' => 1,

                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'emp_image' =>  null,
                'emp_no' => '25031479',

                'fname' => 'Marwin',
                'mname' => null,
                'lname' => 'Reyes',
                'ext' => null,

                'is_active' => 1,
                'is_completed' => 1,

                'created_by' => 1,
                'created_at' => now(),
            ],
    ]);

    }
}
