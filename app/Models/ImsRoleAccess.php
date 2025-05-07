<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImsRoleAccess extends Model
{
    use HasFactory;
    protected $fillable=[
        'role_id',
        'file_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function system_file(){
        return $this->belongsTo(ImsSystemFile::class,'file_id');
    }

    public function system_file_layer(){
        return $this->hasMany(ImsSystemFileLayer::class,'file_id','file_id');
    }

    public function role()
    {
        return $this->belongsTo(ImsRole::class,'role_id');
    }
}
