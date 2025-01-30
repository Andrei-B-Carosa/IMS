<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisRoleAccess extends Model
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
        return $this->belongsTo(HrisSystemFile::class,'file_id');
    }

    public function role()
    {
        return $this->belongsTo(HrisRole::class,'role_id');
    }
}
