<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImsSystemFileLayer extends Model
{
    use HasFactory;

    public function system_file(){
        return $this->belongsTo(ImsSystemFile::class,'file_id');
    }

    public function system_layer(){
        return $this->belongsTo(ImsSystemLayer::class,'layer_id');
    }
}
