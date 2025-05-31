<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItem extends Model
{
    protected $fillable = [
        'item_type_id',
        'item_brand_id',
        'name',
        'description',
        'price',
        'is_active',
        'remarks',
        'supplier_id',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            if (is_null($item->is_active)) {
                $item->is_active = 1;
            }
        });

        static::updating(function ($item) {
            if ($item->is_deleted == 1) {
                $item->is_active = 0;
            }
        });

    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public static function getItemId($name)
    {
        return self::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->where('is_active', 1)
            ->value('id');
    }

    public function item_brand()
    {
        return $this->belongsTo(ImsItemBrand::class,'item_brand_id');
    }


    public function item_type()
    {
        return $this->belongsTo(ImsItemType::class,'item_type_id');
    }

    public static function registerItem($array,$item_type)
    {
        $item_brand_id = $array['brand']?ImsItemBrand::getBrandId($array['brand']):null;
        $item_type_id = ImsItemType::getItemTypeId($item_type);

        return self::updateOrCreate([
            'name' => $array['name'],
            'item_brand_id'=>$item_brand_id,
            'item_type_id'=>$item_type_id,
        ],
        [
            'description' => $array['description']
        ]);
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
