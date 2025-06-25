<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsAccountabilityIssuedTo extends Model
{
    //ImsAccountabilityIssuedTo

    protected $table = 'ims_accountability_issued_to';
    protected $fillable = [
        'id',
        'accountability_id' ,
        'emp_id' ,
        'status',
        'remarks',
        'department_id',
        'issued_at',
        'removed_at',
        'position_id',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];


 /***
  * 1= Current accountable
    2= Removed as accountable
  */
    protected static function booted()
    {
        static::creating(function ($item) {
            if (is_null($item->status)) {
                $item->status = 1;
            }
        });

        // static::updating(function ($item) {
        //     if ($item->is_deleted == 1) {
        //         $item->is_active = 0;
        //     }
        // });

    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }

     public function accountability()
    {
        return $this->belongsTo(ImsAccountability::class,'accountability_id');
    }
}
