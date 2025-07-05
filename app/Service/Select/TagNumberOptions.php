<?php

namespace App\Service\Select;

use App\Models\HrisCompanyLocation;
use App\Models\ImsItemInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TagNumberOptions
{

    public function list(Request $rq)
    {
        // $query = ImsItemInventory::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('status', $rq->view));
        // return match($rq->type){
        //     // 'options' => $this->options($rq,$query),
        //     'filter' => $this->filter($rq,$query),
        // };

        $query = ImsItemInventory::query()
        ->where('is_deleted', null)
        ->when($rq->view != 'all', fn($q) => $q->where('status', $rq->view))
        ->when($rq->search, function($q) use ($rq) {
            $q->where(function($sub) use ($rq) {
                $sub->whereRaw('CAST(id AS CHAR) LIKE ?', ["%{$rq->search}%"])
                    ->orWhere('tag_number', 'LIKE', "%{$rq->search}%");
            })
            ->orderByRaw("CAST(id AS CHAR) = ? DESC", [$rq->search])
            ->orderByRaw("CAST(id AS CHAR) LIKE ? DESC", ["{$rq->search}%"]);
        })
        ->limit(20);

        $results = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->generate_tag_number(),
            ];
        });

        return response()->json($results);
    }

    // public function options($rq,$query)
    // {
    //     $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
    //     $data = $query->get();
    //     if ($data->isNotEmpty()) {
    //         $html = '<option></option>';
    //         foreach ($data as $row) {
    //             $selected = $search === $row->id ? 'selected' : '';
    //             $id = Crypt::encrypt($row->id);
    //             $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->name).'</option>';
    //         }
    //         return $html;
    //     } else {
    //         return '<option disabled>No Available Option</option>';
    //     }
    // }

    // public function filter($rq,$query)
    // {
    //     $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
    //     $data = $query->get();
    //     if ($data->isNotEmpty()) {
    //         $html = '<option value="all" selected>Show All</option>';
    //         foreach ($data as $row) {
    //             $selected = $search === $row->id ? 'selected' : '';
    //             $id = Crypt::encrypt($row->id);
    //             $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->generate_tag_number()).'</option>';
    //         }
    //         return $html;
    //     } else {
    //         return '<option disabled>No Available Option</option>';
    //     }
    // }

}
