<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImsItemInventory extends Model
{
    /*
    0=Disposed
    1=Available
    2=Issued
    3=Temporary Issued
    4=Under Repair
    5=Under Warranty
    */

    protected $fillable = [
        'id',
        'item_type_id',
        'company_location_id',
        'item_brand_id',
        'name',
        'tag_number',
        'description',
        'serial_number',
        'price',
        'received_at',
        'received_by',
        'supplier_id',
        'warranty_end_at',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];


    public function item_brand()
    {
        return $this->belongsTo(ImsItemBrand::class,'item_brand_id');
    }


    public function item_type()
    {
        return $this->belongsTo(ImsItemType::class,'item_type_id');
    }

    public function company_location()
    {
        return $this->belongsTo(HrisCompanyLocation::class,'company_location_id');
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

    public function received_by_emp()
    {
        return $this->belongsTo(Employee::class,'received_by')->withDefault();
    }

    public function generate_tag_number()
    {
        $companyLocationName = $this->company_location ? $this->company_location->location_code : 'UNKNOWN';
        $itemTypeCode = $this->item_type ? $this->item_type->item_code : 'XXX';
        $itemId = $this->id ?? 0;

        return strtoupper($companyLocationName). '-' .$itemTypeCode. '-'.str_pad($itemId, 5, '0', STR_PAD_LEFT);
    }

    public function accountability_item()
    {
        return $this->hasMany(ImsAccountabilityItem::class,'item_inventory_id','id');
    }

    public function active_accountability_item()
    {
        return $this->hasOne(ImsAccountabilityItem::class,'item_inventory_id','id')->where('status',1);
    }


    public function repair_log()
    {
        return $this->hasMany(ImsItemRepairLog::class,'item_inventory_id','id');
    }

    public function status_badge(){
        $status = [
            0 => ['warning', 'Disposed'],
            1 => ['info', 'Available'],
            2 => ['success', 'Issued'],
            3 => ['secondary', 'Temporary Issued'],
            4 => ['danger', 'Under Repair'],
        ];

        if (!isset($status[$this->status])) {
            return '<span class="badge badge-light">Unknown</span>';
        }

        [$color, $label] = $status[$this->status];

        return "<span class=\"badge badge-{$color}\">{$label}</span>";
    }

    public function description_construct()
    {
        if ($this->item_type_id == 1 || $this->item_type_id == 8) {
        $array = json_decode($this->description, true);

        $storage_html = '';
        if (!empty($array['storage'])) {
            $storage = json_decode($array['storage'], true);
            foreach ($storage as $row) {
                $storage_html .= 'Storage: ' . ($row['description'] ?? '') . '<br>';
            }
        }

        $ram_html = '';
        if (!empty($array['ram'])) {
            $ram = json_decode($array['ram'], true);
            $ram_html = collect($ram)
                ->groupBy('name')
                ->map(function ($items, $size) {
                    return (count($items) > 1 ? count($items) . 'x' : '') . $size;
                })
                ->implode(', ');
        }

        $gpu_html = '';
        if (!empty($array['gpu'])) {
            $gpu = json_decode($array['gpu'], true);
            foreach ($gpu as $row) {
                if (($row['type'] ?? '') === 'Integrated') {
                    continue;
                }
                $gpu_html .= 'GPU: ' . ($row['description'] ?? '') . '<br>';
            }
        }

        $description = '<div class="fs-6">'
            . ($this->item_type_id == 8 ? 'Model: ' . ($array['model'] ?? '') . '<br>' : '')
            . 'CPU: ' . ($array['cpu'] ?? '') . '<br>'
            . 'RAM: ' . $ram_html . '<br>'
            . $storage_html
            . 'OS: ' . ($array['windows_version'] ?? '') . '<br>'
            . $gpu_html
            . 'Device Name: ' . ($array['device_name'] ?? '') . '<br>'
            . '</div>';

        return $description;
    }

    // If item_type_id doesn't match
    return $this->description;
    }
}
