<?php

namespace App\Service\Select;

use App\Models\HrisEmploymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EmploymentTypeOptions
{
    public function list(Request $rq)
    {
        $query = HrisEmploymentType::where([['is_deleted',null],['is_active',1]]);
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
