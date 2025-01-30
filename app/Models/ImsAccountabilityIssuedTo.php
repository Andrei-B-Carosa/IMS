<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsAccountabilityIssuedTo extends Model
{
    //ImsAccountabilityIssuedTo

    protected $table = 'ims_accountability_issued_to';
    protected $fillable = [
        'emp_id' ,
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];
}
