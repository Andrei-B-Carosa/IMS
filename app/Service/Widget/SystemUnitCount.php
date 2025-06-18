<?php

namespace App\Service\Widget;

use App\Models\ImsItem;
use App\Models\ImsItemInventory;
use App\Models\ImsItemType;

class SystemUnitCount
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
        $desktop_id = ImsItemType::getDesktopId();
        $count = ImsItemInventory::where([['item_type_id',$desktop_id],['is_deleted',null],['status','!=',0]])->count();
        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$count]);
    }
}
