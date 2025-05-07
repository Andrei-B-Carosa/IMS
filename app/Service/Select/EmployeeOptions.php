<?php

namespace App\Service\Select;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EmployeeOptions
{

    public function list(Request $rq)
    {
        $query = Employee::where('is_deleted',null)->when($rq->view!='all', fn($q) => $q->where('is_active', $rq->view));

        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        $html = '<option></option>';

        return match($rq->type){
            'employee' => $this->get_employee($query,$search,$html),
            'mis_personnel' => $this->get_mis_personnel($query,$search,$html),
        };
    }

    public function get_employee($query,$search,$html)
    {
        $data = $query->get();
        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }
        foreach ($data as $row) {
            $selected = $search === $row->id ? 'selected' : '';
            $id = Crypt::encrypt($row->id);
            $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->fullname()).'</option>';
        }
        return $html;
    }

    public function get_mis_personnel($query,$search,$html)
    {
        $data = $query->whereHas('emp_details',function($q){
            $q->where('department_id',2);
        })->get();

        if ($data->isEmpty()) {
            return '<option disabled>No Available Option</option>';
        }
        foreach ($data as $row) {
            $selected = $search === $row->id ? 'selected' : '';
            $id = Crypt::encrypt($row->id);
            $html .= '<option value="'.e($id).'"'.e($selected).'>'.e($row->fullname()).'</option>';
        }
        return $html;

    }
}
