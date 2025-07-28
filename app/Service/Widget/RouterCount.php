<?php

namespace App\Service\Widget;

use App\Models\ImsItemInventory;
use App\Models\ImsItemType;

class RouterCount
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
        $router_id = ImsItemType::getItemTypeId('router');
        $modem_id = ImsItemType::getItemTypeId('modem');
        $count = ImsItemInventory::whereIn('item_type_id',[$router_id,$modem_id])->where([['is_deleted',null],['status','!=',0]])->count();
        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$count]);
    }
}
