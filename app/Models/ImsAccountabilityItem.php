<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsAccountabilityItem extends Model
{
    protected $fillable = [
        'accountability_id' ,
        'item_inventory_id' ,
        'status',
        'remarks',
        'issued_at',
        'removed_at',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

    /***
    * 1= Issued
      2= Returned
    */
  protected static function booted()
  {
      static::creating(function ($item) {
          if (is_null($item->status)) {
              $item->status = 1;
          }
      });

      // static::updating(function ($item) {
      //     if ($item->is_deleted == 1) {
      //         $item->is_active = 0;
      //     }
      // });

  }

  public function item_inventory()
  {
    return $this->belongsTo(ImsItemInventory::class,'item_inventory_id');
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
