<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemInventory extends Model
{
    /*
    0=Disposed
    1=Available
    2=Issued
    3=Temporary Issued
    4=Under Repair
    */

    protected $fillable = [
        'item_type_id',
        'item_brand_id',
        'name',
        'description',
        'serial_number',
        'price',
        'received_at',
        'received_by',
        'supplier_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];


    public function item_brand()
    {
        return $this->belongsTo(ImsItemBrand::class,'item_brand_id');
    }


    public function item_type()
    {
        return $this->belongsTo(ImsItemType::class,'item_type_id');
    }
}
