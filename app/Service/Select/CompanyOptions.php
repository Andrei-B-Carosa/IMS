<?php

namespace App\Services\Reusable\Select;

use App\Models\HrisCompany;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisLeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CompanyOptions
{

    public function list(Request $rq)
    {
        $query = HrisCompany::where([['is_deleted',null],['is_active',1]]);
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
                $html .='<option value="'.e($id).'"'.e($selected).'>'
                            .e($row->name).
                            // e($row->is_active==2 ? ' (this company is inactive)':'').
                        '</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }


}
