<?php

namespace App\Service\Select;

use App\Models\HrisCompany;
use App\Models\HrisDepartment;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisGroup;
use App\Models\HrisGroupApprover;
use App\Models\HrisLeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class GroupOptions
{

    public function list(Request $rq)
    {
        $group_id = HrisGroupApprover::where([['emp_id',Auth::user()->emp_id],['is_active',1]])->pluck('group_id');
        $query = HrisGroup::where([['is_deleted',null]])->whereIn('id',$group_id);
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
