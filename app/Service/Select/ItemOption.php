<?php

namespace App\Service\Select;

use App\Models\Employee;
use App\Models\ImsItem;
use App\Models\ImsItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ItemOption
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function list(Request $rq)
    {
        $query = ImsItem::with(['item_brand','item_type'])->where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'monitor' => $this->get_monitor($query,$search,$html),
            'accessories' => $this->get_other_accessories($query,$search,$html),
            'search_ram'=> $this->search_ram($rq,$query,$html),
            'search_storage'=> $this->search_storage($rq,$query,$html),
            'search_gpu'=> $this->search_gpu($rq,$query,$html),
        };
    }


    public function get_monitor($query,$search,$html)
    {
        $monitor_id = ImsItemType::whereRaw('LOWER(name) = ?', ['monitor'])->where('is_active', 1)->value('id');
        if ($monitor_id === null) {
            return '<option disabled>No Available Option</option>';
        }

        $data = $query->where('item_type_id',$monitor_id)->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = $search === $row->id ? 'selected' : '';
            $name = $row->item_brand->name.' '.$row->item_type->name;

            $html .= '<option value="'.e($value).'"'.e($selected).'>'.e($name).'</option>';
        }
        return $html;
    }

    public function get_other_accessories($query,$search,$html)
    {
        $monitor_id = ImsItemType::whereRaw('LOWER(name) = ?', ['monitor'])->where('is_active', 1)->value('id');
        if ($monitor_id === null) {
            return '<option disabled>No Available Option</option>';
        }

        $data = $query->where('item_type_id','!=',$monitor_id)->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = $search === $row->id ? 'selected' : '';
            $name = $row->name.' '.$row->description;

            $html .= '<option value="'.e($value).'"'.e($selected).'>'
                        .e($name).
                    '</option>';
        }
        return $html;
    }

    public function search_ram($rq,$query,$html)
    {
        $ram_id = ImsItemType::whereRaw('LOWER(name) = ?', ['ram'])->where('is_active', 1)->value('id');
        if ($ram_id === null) {
            return '<option disabled>No Available Option</option>';
        }

        $data = $query->where('item_type_id',$ram_id)->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = trim(preg_replace('/\s+/', ' ', strtolower($rq->search))) === trim(preg_replace('/\s+/', ' ', strtolower($row->name))) ? 'selected' : '';
            $name = $row->name;

            $html .= '<option value="'.e($value).'"'.e($selected).'>'
                        .e($name).
                    '</option>';
        }
        return $html;
    }

    public function search_storage($rq,$query,$html)
    {
        $names = ['ssd', 'hdd', 'nvme'];
        $storage_ids = ImsItemType::whereIn(DB::raw('LOWER(name)'), $names)->where('is_active', 1)->pluck('id');

        // ->where(function ($query) use ($names) {
        //     foreach ($names as $name) {
        //         $query->orWhere('name', 'LIKE', "%$name%");
        //     }
        // })
        if ($storage_ids === null) {
            return '<option disabled>No Available Option</option>';
        }

        $data = $query->whereIn('item_type_id',$storage_ids)->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = trim(preg_replace('/\s+/', ' ', strtolower($rq->search))) === trim(preg_replace('/\s+/', ' ', strtolower($row->name))) ? 'selected' : '';
            $name = $row->name;

            $html .= '<option value="'.e($value).'"'.e($selected).'>'
                        .e($name).
                    '</option>';
        }
        return $html;
    }

    public function search_gpu($rq,$query,$html)
    {
        $names = ['gpu'];
        $storage_ids = ImsItemType::whereIn(DB::raw('LOWER(name)'), $names)->where('is_active', 1)->pluck('id');

        // ->where(function ($query) use ($names) {
        //     foreach ($names as $name) {
        //         $query->orWhere('name', 'LIKE', "%$name%");
        //     }
        // })
        if ($storage_ids === null) {
            return '<option disabled>No Available Option</option>';
        }

        $data = $query->whereIn('item_type_id',$storage_ids)->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = trim(preg_replace('/\s+/', ' ', strtolower($rq->search))) === trim(preg_replace('/\s+/', ' ', strtolower($row->name))) ? 'selected' : '';
            $name = $row->name;

            $html .= '<option value="'.e($value).'"'.e($selected).'>'
                        .e($name).
                    '</option>';
        }
        return $html;
    }
}
