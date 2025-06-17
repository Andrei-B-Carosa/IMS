<?php

namespace App\Service\Select;

use App\Models\ImsItemInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InventoryOption
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
        $query = ImsItemInventory::with(['item_type'])->where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('status', $rq->view));
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'accountability_items' => $this->get_accountability_items($query,$search,$html),
            'material_issuance_items' => $this->get_material_issuance_items($query,$search,$html),
        };
    }

    public function get_accountability_items($query,$search,$html)
    {
        $data = $query->whereHas('item_type',function($q){
            $q->where('display_to',1)->orWhere('display_to',null);
        })->get();

        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        foreach ($data as $row) {
            $value = Crypt::encrypt($row->id);
            $selected = $search === $row->id ? 'selected' : '';
            $name = $row->name ?? $row->description;
            $name = $name.' ( '. $row->generate_tag_number() .' )';

            $html .= '<option value="'.e($value).'"'.e($selected).'>'
                        .e($name).
                    '</option>';
        }
        return $html;
    }


    public function get_material_issuance_items($query,$search,$html)
    {
        $data = $query->whereHas('item_type', function ($q) {
            $q->where('display_to', 2)->orWhereNull('display_to');
        })->get();

        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }

        // Group the data by 'name'
        $grouped = $data->groupBy('name');
        $html = '<option></option>'; // Optional empty option

        foreach ($grouped as $name => $items) {
            $first = $items->first(); // Get one representative record
            $count = $items->count();
            $value = Crypt::encrypt($first->id); // Use the first item's ID for value
            $selected = $search === $first->id ? 'selected' : '';
            $displayName = $name ?? $first->description;

            $html .= '<option value="'.e($value).'" '.$selected.'>'
                        .e($displayName).' ( Available : X'.$count.')'
                    .'</option>';
        }

        return $html;
    }
}
