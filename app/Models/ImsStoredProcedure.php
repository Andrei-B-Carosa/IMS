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
}
