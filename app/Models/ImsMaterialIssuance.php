<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsMaterialIssuance extends Model
{
    protected $fillable = [
        'form_no',
        'mrs_no',
        'issued_at',
        'issued_by',
        'received_by',
        'remarks',
        'signature',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

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

    public function issued_to()
    {
        return $this->hasMany(ImsMaterialIssuanceIssuedTo::class,'material_issuance_id');
    }

    public function issued_item()
    {
        return $this->hasMany(ImsMaterialIssuanceItem::class,'material_issuance_id');
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

    public function issued_by_emp()
    {
        return $this->belongsTo(Employee::class,'issued_by')->withDefault();
    }

    public function received_by_emp()
    {
        return $this->belongsTo(Employee::class,'received_by')->withDefault();
    }
}
