<?php

namespace App\Service\Select;

use App\Models\HrisEmployeeLeaveRequest;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Models\HrisEmployeeOvertimeRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilterYearOptions
{

    public function get_request_year(Request $rq)
    {
        try{
            $user_id = isset($rq->id) && $rq->id=='true'? Auth::user()->emp_id:false;
            $data =  match($rq->type){
                '1'=> self::get_overtime_year($user_id),
                '2'=> self::get_leave_year($user_id),
                '3'=> self::get_ob_year($user_id),
            };
            if ($data->isNotEmpty()) {
                $html = '<option></option>';
                foreach ($data as $year) {
                    $html .= '<option value="'.e($year).'">'.e($year).'</option>';
                }
                return $html;
            } else {
                return '<option disabled>No Available Option</option>';
            }
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function get_overtime_year($user_id=null)
    {
        return HrisEmployeeOvertimeRequest::query()
            ->when($user_id, fn($q) =>
                $q->where([
                    ['emp_id', $user_id],
                    ['is_deleted', null]
                ])
            )
            ->selectRaw('YEAR(overtime_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public function get_leave_year($user_id=null)
    {
        return HrisEmployeeLeaveRequest::query()
        ->when($user_id, fn($q) =>
            $q->where([
                ['emp_id', $user_id],
                ['is_deleted', null]
            ])
        )
        ->selectRaw('YEAR(leave_filing_date) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
    }


    public function get_ob_year($user_id)
    {
        return HrisEmployeeOfficialBusinessRequest::query()
        ->where([['emp_id',$user_id],['is_deleted',null]])
        ->selectRaw('YEAR(ob_filing_date) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
    }

}
