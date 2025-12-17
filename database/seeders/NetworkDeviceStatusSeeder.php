<?php

namespace Database\Seeders;

use App\Models\ImsItemInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NetworkDeviceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ImsItemInventory::where([['is_deleted',null],['status','!=','0']])->whereIn('item_type_id',[10,25,26])->get();
        foreach ($items as $item) {
            $item->status = 6;
            $item->save();
        }
    }
}
