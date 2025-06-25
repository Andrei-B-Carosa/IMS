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
        'id',
        'item_type_id',
        'company_location_id',
        'item_brand_id',
        'name',
        'tag_number',
        'description',
        'serial_number',
        'price',
        'received_at',
        'received_by',
        'supplier_id',
        'warranty_end_at',
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

    public function company_location()
    {
        return $this->belongsTo(HrisCompanyLocation::class,'company_location_id');
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();
    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by');
    }

    public function received_by_emp()
    {
        return $this->belongsTo(Employee::class,'received_by')->withDefault();
    }

    public function generate_tag_number()
    {
        $companyLocationName = $this->company_location ? $this->company_location->location_code : 'UNKNOWN';
        $itemTypeCode = $this->item_type ? $this->item_type->item_code : 'XXX';
        $itemId = $this->id ?? 0;

        return strtoupper($companyLocationName). '-' .$itemTypeCode. '-'.str_pad($itemId, 5, '0', STR_PAD_LEFT);
    }

    public function accountability_item()
    {
        return $this->hasMany(ImsAccountabilityItem::class,'item_inventory_id','id');
    }

    public function active_accountability_item()
    {
        return $this->hasOne(ImsAccountabilityItem::class,'item_inventory_id','id')->where('status',1);
    }


    public function repair_log()
    {
        return $this->hasMany(ImsItemRepairLog::class,'item_inventory_id','id');
    }
}
