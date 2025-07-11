<?php

namespace Database\Seeders;

use App\Models\ImsItemInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImsTagNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ImsItemInventory::where([['tag_number',null],['is_deleted',null],['status','!=','0']])->get();

        foreach ($items as $item) {
            // call your model function to get tag number
            $tagNumber = $item->generate_tag_number();

            // store it in your DB (assuming you have a `tag_number` column)
            $item->tag_number = $tagNumber;
            $item->save();
        }
    }
}
