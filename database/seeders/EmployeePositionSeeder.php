<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_employee_positions')->insert([
            [
                'emp_id' => 1,
                'position_id' => 33,
                'department_id' => 2,
                'company_id' => 1,
                // 'company_location_id' =>1,
                // 'section_id' => $section->id,
                // 'classification_id' => $classification->id,
                // 'employment_id' => $employmentType->id,
                // 'date_employed' => Carbon::now(),
                'is_active' => 1,
                'work_status' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Replace with actual user ID if needed
                'created_at' => Carbon::now(),
            ],
            [
                'emp_id' => 2,
                'position_id' => 27,
                'department_id' => 2,
                'company_id' => 1,
                // 'company_location_id' =>1,
                // 'section_id' => $section->id,
                // 'classification_id' => $classification->id,
                // 'employment_id' => $employmentType->id,
                // 'date_employed' => Carbon::now(),
                'is_active' => 1,
                'work_status' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Replace with actual user ID if needed
                'created_at' => Carbon::now(),
            ],
            [
                'emp_id' => 3,
                'position_id' => 27,
                'department_id' => 2,
                'company_id' => 1,
                // 'company_location_id' =>1,
                // 'section_id' => $section->id,
                // 'classification_id' => $classification->id,
                // 'employment_id' => $employmentType->id,
                // 'date_employed' => Carbon::now(),
                'is_active' => 1,
                'work_status' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Replace with actual user ID if needed
                'created_at' => Carbon::now(),
            ],
            [
                'emp_id' => 4,
                'position_id' => 32,
                'department_id' => 2,
                'company_id' => 1,
                // 'company_location_id' =>1,
                // 'section_id' => $section->id,
                // 'classification_id' => $classification->id,
                // 'employment_id' => $employmentType->id,
                // 'date_employed' => Carbon::now(),
                'is_active' => 1,
                'work_status' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Replace with actual user ID if needed
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
