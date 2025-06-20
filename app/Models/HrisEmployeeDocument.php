<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id' ,
        'file_id' ,
        'others',
        'filename' ,
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
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
