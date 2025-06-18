<?php

namespace App\Service\Widget;

use App\Models\ImsItemInventory;
use App\Models\ImsItemType;

class CellphoneCount
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
        $cellphone_id = ImsItemType::getCellphoneId();
        $count = ImsItemInventory::where([['item_type_id',$cellphone_id],['is_deleted',null],['status','!=',0]])->count();
        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$count]);
    }
}
