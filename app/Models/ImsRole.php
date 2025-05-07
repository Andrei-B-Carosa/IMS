<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImsRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'is_active', 'created_by', 'updated_by',
    ];

    public function role_access()
    {
        return $this->hasMany(ImsRoleAccess::class,'role_id');
    }

    public function user_roles()
    {
        return $this->hasMany(ImsUserRole::class,'role_id');

    }
}
