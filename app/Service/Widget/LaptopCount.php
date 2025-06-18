<?php

namespace App\Service\Widget;

use App\Models\ImsItemInventory;
use App\Models\ImsItemType;

class LaptopCount
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function show()
    {
        $laptop_id = ImsItemType::getLaptopId();
        $count = ImsItemInventory::where([['item_type_id',$laptop_id],['is_deleted',null],['status','!=',0]])->count();
        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$count]);
    }
}
