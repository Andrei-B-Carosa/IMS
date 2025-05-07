<?php

namespace App\Service\Select;

use App\Models\HrisCompany;
use App\Models\HrisDepartment;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisLeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DepartmentOptions
{

    public function list(Request $rq)
    {
        $query = HrisDepartment::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));

        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'department' => $this->get_department($query,$search,$html),
        };
    }

    public function get_department($query,$search,$html)
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
