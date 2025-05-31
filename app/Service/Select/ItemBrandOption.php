<?php

namespace App\Service\Select;

use App\Models\ImsItemBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ItemBrandOption
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
        $query = ImsItemBrand::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));

        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'get_item_brand' => $this->get_item_brand($query,$search,$html),
        };
    }


    public function get_item_brand($query,$search,$html)
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
}
