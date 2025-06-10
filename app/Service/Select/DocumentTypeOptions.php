<?php

namespace App\Service\Select;

use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisLeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DocumentTypeOptions
{

    public function list(Request $rq)
    {
        $data = config('document_values.document_type');
        return match($rq->type){
            'options' => $this->options($rq,$data),
        };
    }


    public function options($rq,$data)
    {
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        if ($data) {
            $html = '<option></option>';
            foreach ($data as $key => $row) {
                $selected = $search == $key ? 'selected' : '';
                $html .= '<option value="'.e($key).'"'.e($selected).'>'.e($row).'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }
}
