<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisCompanyLocation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_companies')->insert([
            [
                'name' => 'Company 1',
                'description' => 'RVL Movers Corporation',
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('hris_company_locations')->insert([
            [
                'company_id'=>1,
                'name' => strtoupper('RVL DAYSTAR'),
                'description' => 'RVL DAYSTAR',
                'location_code' => 'DSTR',
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id'=>1,
                'name' => strtoupper('RVL CUPANG'),
                'description' => 'RVL CUPANG',
                'location_code' => 'CPNG',
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id'=>1,
                'name' => strtoupper('RVL BATANGAS'),
                'description' => 'RVL BATANGAS',
                'location_code' => 'BTNG',
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
