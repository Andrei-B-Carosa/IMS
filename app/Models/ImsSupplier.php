<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsSupplier extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(preg_replace('/\s+/', ' ', strtoupper($value)));
    }

    public static function getBrandId($name)
    {
        $search = trim(preg_replace('/\s+/', ' ', $name));

        // Try to get existing brand ID (case-insensitive)
        $brand = self::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($search)])
            ->where('is_active', 1)
            ->first();

        if ($brand) {
            return $brand->id;
        }

        // If not found, insert new brand and return the ID
        return self::create([
            'name' => $name,
            'is_active' => 1
        ])->id;
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
