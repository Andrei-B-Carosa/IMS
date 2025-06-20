<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisCompanyLocation extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'name',
        'description',
        'location_code',
        'is_active',
        'created_by',
        'updated_by',
        'company_id',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];


    public function company()
    {
        return $this->belongsTo(HrisCompany::class,'company_id');
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
