<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisSystemFile extends Model
{
    use HasFactory;

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

    public function file_layer(){
        return $this->hasMany(HrisSystemFileLayer::class,'file_id','id');
    }

    public function role_access(){
        return $this->hasMany(HrisRoleAccess::class,'file_id');
    }
}
