<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisUserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'role_id',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function role(){
        return $this->belongsTo(HrisRole::class,'role_id');
    }


    public function employee(){
        return $this->hasOne(Employee::class,'emp_id');
    }
}
