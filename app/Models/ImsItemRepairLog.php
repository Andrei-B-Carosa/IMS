<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemRepairLog extends Model
{
    /**
     * repair_type
     * 1=hardware
     * 2=software
     *
     * 1=in progress
     * 2=resolved
     * 3=not repairable
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'item_inventory_id',
        'item_inventory_status',
        'issued_by',
        'repair_type',
        'start_at',
        'end_at',
        'description',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    public function item_inventory()
    {
        return $this->belongsTo(ImsItemInventory::class,'item_inventory_id');
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
}
