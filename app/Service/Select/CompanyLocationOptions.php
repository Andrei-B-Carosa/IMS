<?php

namespace App\Service\Select;

use App\Models\HrisCompanyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CompanyLocationOptions
{

    public function list(Request $rq)
    {
        $query = HrisCompanyLocation::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));
        return match($rq->type){
            'options' => $this->options($rq,$query),
        };
    }

    public function options($rq,$query)
    {
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $data = $query->get();

        if ($data->isNotEmpty()) {
            $html = '<option></option>';
            foreach ($data as $row) {
                $selected = $search === $row->id ? 'selected' : '';
                $id = Crypt::encrypt($row->id);
                $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->name).'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }

}
