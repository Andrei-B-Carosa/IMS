<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemBrand extends Model
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

}
