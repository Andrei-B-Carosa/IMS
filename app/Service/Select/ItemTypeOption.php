<?php

namespace App\Service\Select;

use App\Models\ImsItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ItemTypeOption
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
        $query = ImsItemType::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));

        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'get_item_type' => $this->get_item_type($query,$search,$html),
            'filter_item_type' => $this->filter_item_type($query,$search,$html),
        };
    }


    public function get_item_type($query,$search,$html)
    {
        $data = $query->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }
        foreach ($data as $row) {
            $selected = $search === $row->id ? 'selected' : '';
            $id = Crypt::encrypt($row->id);
            $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->name).'</option>';
        }
        return $html;
    }

    public function filter_item_type($query,$search,$html)
    {
        $ssd_id = ImsItemType::getSSDId();
        $hdd_id = ImsItemType::getHDDId();
        $gpu_id = ImsItemType::getGPUId();
        $ram_id = ImsItemType::getRAMId();
        $ink_id = ImsItemType::getItemTypeId('ink');

        $data = $query->whereNotIn('id',[$ssd_id,$hdd_id,$gpu_id,$ram_id,$ink_id])->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        $html = '<option value="all" selected>Show All</option>';
        foreach ($data as $row) {
            $id = Crypt::encrypt($row->id);
            $html .= '<option value="'.e($id).'">'.e($row->name).'</option>';
        }
        return $html;
    }

}
