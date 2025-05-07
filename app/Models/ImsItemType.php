<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'item_number',
        'is_active',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    // Mutator for the 'name' attribute
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtoupper($value));
    }


    public static function getDesktopId()
    {
        return self::whereRaw('LOWER(name) = ?', ['system unit'])
                   ->where('is_active', 1)
                   ->value('id');
    }

    public static function getMonitorId()
    {
        return self::whereRaw('LOWER(name) = ?', ['monitor'])
                   ->where('is_active', 1)
                   ->value('id');
    }

    public static function getItemTypeId($name)
    {
        $search = trim(preg_replace('/\s+/', ' ', $name));

        // Try to get existing item type ID (case-insensitive)
        $type = self::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($search)])
            ->where('is_active', 1)
            ->first();

        if ($type) {
            return $type->id;
        }

        // If not found, insert new item type and return the ID
        return self::create([
            'name' => $name,
            'description'=>null,
            'is_active' => 1
        ])->id;
    }

}
