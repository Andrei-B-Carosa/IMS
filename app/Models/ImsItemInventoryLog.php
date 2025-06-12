<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemInventoryLog extends Model
{
    protected $fillable = [
        'id',
        'item_inventory_id',
        'emp_id',
        'activity_table',
        'activity_type',
        'activity_log',
        'old_value',
        'new_value',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
}
