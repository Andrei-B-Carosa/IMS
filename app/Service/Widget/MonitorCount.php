<?php

namespace App\Service\Widget;

use App\Models\ImsItemInventory;
use App\Models\ImsItemType;

class MonitorCount
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
        $monitor_id = ImsItemType::getMonitorId();
        $count = ImsItemInventory::where([['item_type_id',$monitor_id],['is_deleted',null],['status','!=',0]])->count();
        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$count]);
    }
}
