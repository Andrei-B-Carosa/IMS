<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'is_active', 'created_by', 'updated_by',
    ];

    public function role_access()
    {
        return $this->hasMany(HrisRoleAccess::class,'role_id');
    }

    public function user_roles()
    {
        return $this->hasMany(HrisUserRole::class,'role_id');

    }
}
