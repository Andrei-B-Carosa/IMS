<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImsStoredProcedure extends Model
{
    public static function sp_get_inventory_accountability(
            $filter_status,
            $filter_location,
            $filter_year,
            $is_consumable,
            $filter_category,
    ){
        return static::hydrate(
            DB::select('call sp_get_inventory_accountability(?,?,?,?,?)',[
                $filter_status,
                $filter_location,
                $filter_year,
                $is_consumable,
                $filter_category,
            ])
        );
    }

    public static function sp_get_repair_logs(
            $filter_status,
            $filter_location,
            $filter_category,
            $item_inventory_id=null,
    ){
        return static::hydrate(
            DB::select('call sp_get_repair_logs(?,?,?,?)',[
                $filter_status,
                $filter_location,
                $filter_category,
                $item_inventory_id,
            ])
        );
    }


    public static function sp_get_accountability_list(
            $filter_status,
    ){
        return static::hydrate(
            DB::select('call sp_get_accountability_list(?)',[
                $filter_status,
            ])
        );
    }

    public static function sp_get_accountability_history(
            $item_inventory_id,
    ){
        return static::hydrate(
            DB::select('call sp_get_accountability_history(?)',[
                $item_inventory_id,
            ])
        );
    }
}
