<?php

namespace App\Service\Select;

use App\Models\HrisCompany;
use App\Models\HrisDepartment;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisLeaveType;
use App\Models\HrisSection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SectionOptions
{

    public function list(Request $rq)
    {
        $query = HrisSection::where([['is_deleted',null],['is_active',1]]);
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
