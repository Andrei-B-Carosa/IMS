<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FamilyBackground extends Controller
{
    protected $isRegisterEmployee = false;

    public function view($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        return view($components.'family-background', compact('employee','isRegisterEmployee'))->render();
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();
            $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;
            $user_id = Auth::user()->id;

            $attribute = ['id' =>$emp_id];
            $value = [];
            if($rq->column == 'spouse'){
                $value = [
                    'spouse_fname'=>$rq->spouse_fname,
                    'spouse_lname'=>$rq->spouse_lname,
                    'spouse_mname'=>$rq->spouse_mname,
                    'spouse_occupation'=>$rq->spouse_occupation,
                    'spouse_employer'=>$rq->spouse_employer,
                    'spouse_business_address'=>$rq->spouse_business_address,
                ];
            }
            elseif($rq->column == 'father'){
                $value = [
                    'father_fname'=>$rq->father_fname,
                    'father_lname'=>$rq->father_lname,
                    'father_mname'=>$rq->father_mname,
                    'father_ext'=>$rq->ext,
                ];
            }
            elseif($rq->column == 'mother'){
                $value = [
                    'mother_fname'=>$rq->mother_fname,
                    'mother_lname'=>$rq->mother_lname,
                    'mother_mname'=>$rq->mother_mname,
                ];
            }

            if(empty($value)){
                return [ 'status' => 'error','message'=>'Something went wrong, try again later', 'payload'=>'' ];
            }

            $values['updated_by'] = $user_id;
            Employee::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload'=>'' ];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }
}
