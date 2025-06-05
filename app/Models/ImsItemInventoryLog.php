<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemInventoryLog extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
}
