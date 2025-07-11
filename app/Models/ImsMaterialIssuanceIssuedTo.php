<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsMaterialIssuanceIssuedTo extends Model
{
    protected $table = 'ims_material_issuance_issued_to';
    protected $fillable = [
        'material_issuance_id' ,
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

    public function ims_material_issuance()
    {
        return $this->belongsTo(ImsMaterialIssuance::class,'material_issuance_id');
    }
}
