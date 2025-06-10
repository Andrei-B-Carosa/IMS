<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\EmploymentDetails;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisEmployeePosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EmploymentDetails extends Controller
{

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->id);
            // $company_id = Crypt::decrypt($rq->company_id);
            // $location_id = Crypt::decrypt($rq->location_id);
            $department_id = Crypt::decrypt($rq->department_id);
            // $section_id = isset($rq->section_id) ? Crypt::decrypt($rq->section_id):null;
            // $employment_id = Crypt::decrypt($rq->employment_id);
            $position_id = Crypt::decrypt($rq->position_id);
            // $classification_id = Crypt::decrypt($rq->classification_id);

            $query = Employee::find($emp_id);
            if(!$query){
                throw new \Exception("Employee record not found!");
            }

            $attribute = ['emp_id' =>$emp_id];
            $value = [
                // 'company_id'=>$company_id,
                // 'company_location_id'=>$location_id,
                'department_id'=>$department_id,
                // 'section_id'=>$section_id,
                // 'employment_id'=>$employment_id,
                'position_id'=>$position_id,
                // 'classification_id'=>$classification_id,
                // 'is_active'=>$rq->is_active,                                                            //employment_status
                // 'date_employed'=>Carbon::createFromFormat('m-d-Y',$rq->date_employed)->format('Y-m-d'),
                // 'work_status'=>$rq->work_status,
                'updated_by' => $user_id,
            ];
            $query = Employee::find($emp_id);
            if($query->emp_no != $rq->emp_no){
                $query->emp_no  = $rq->emp_no;
                $query->updated_by  = $user_id;
                $query->save();
            }
            
            HrisEmployeePosition::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

}
