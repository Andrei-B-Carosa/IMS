<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use App\Models\HrisEmployeePosition;
use App\Service\Select\ClassificationOptions;
use App\Service\Select\CompanyLocationOptions;
use App\Service\Select\CompanyOptions;
use App\Service\Select\DepartmentOptions;
use App\Service\Select\EmploymentTypeOptions;
use App\Service\Select\PositionOptions;
use App\Service\Select\SectionOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EmployeeRegistration extends Controller
{

    public function form(Request $rq)
    {
        try {
            $emp_id = isset($rq->emp_id) ?Crypt::decrypt($rq->emp_id):null;
            $components = 'components.employee-details.';
            $employee = Employee::with('emp_details','emp_account:id,emp_id,username,c_email')->find($emp_id);

            $view = match($rq->form){
                '1'=>self::personal_details($rq,$components,$employee),
                '2'=>self::employment_details($rq,$components,$employee),
                default=>false,
            };

            if($view === false) { throw new \Exception("Form not found!"); }

            return response()->json([
                'status' =>'success',
                'message' => 'success',
                'payload' => base64_encode($view)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function personal_details($rq,$components,$employee)
    {
        $isRegisterEmployee = true;
        return view($components.'personal-information', compact('employee','isRegisterEmployee'))->render();
    }

    public function employment_details($rq,$components,$employee)
    {
        $isRegisterEmployee = true;
        $emp_details = $employee->emp_details?$employee->emp_details:null;

        $classification_id  = $emp_details? Crypt::encrypt($emp_details->classification_id):null;
        $request = $rq->merge(['id' => $classification_id, 'type'=>'options']);
        $classification = (new ClassificationOptions)->list($request);

        $employment_id  = $emp_details? Crypt::encrypt($emp_details->employment_id):null;
        $request = $rq->merge(['id' => $employment_id, 'type'=>'options']);
        $employment_type = (new EmploymentTypeOptions)->list($request);

        $department_id  = $emp_details? Crypt::encrypt($emp_details->department_id):null;
        $request = $rq->merge(['id' => $department_id, 'type'=>'department','view'=>1]);
        $department = (new DepartmentOptions)->list($request);

        // $section_id  = $emp_details? Crypt::encrypt($emp_details->section_id):null;
        // $request = $rq->merge(['id' => $section_id, 'type'=>'options']);
        // $section = (new SectionOptions)->list($request);

        $position_id  = $emp_details? Crypt::encrypt($emp_details->position_id):null;
        $request = $rq->merge(['id' => $position_id, 'type'=>'position','view'=>1]);
        $position = (new PositionOptions)->list($request);

        $company_id  = $emp_details? Crypt::encrypt($emp_details->company_id):null;
        $request = $rq->merge(['id' => $company_id, 'type'=>'options']);
        $company = (new CompanyOptions)->list($request);

        $company_location_id  = $emp_details? Crypt::encrypt($emp_details->company_location_id):null;
        $request = $rq->merge(['id' => $company_location_id, 'type'=>'options']);
        $company_location = (new CompanyLocationOptions)->list($request);

        $options = [
            'classification'=>$classification,
            'employment_type'=>$employment_type,
            'department'=>$department,
            // 'section'=>$section,
            // 'section'=>$section,
            'position'=>$position,
            'company'=>$company,
            'company_location'=>$company_location,
        ];
        return view($components.'employment-details', compact('employee','isRegisterEmployee','options'))->render();

    }

    public function update(Request $rq)
    {
        try {
            $res = match ($rq->form) {
                '1'=> self::update_personal_info($rq),
                '2'=> self::update_employment_info($rq),
                default => throw new \Exception("Form not found!"),
            };

            if(!$res['status'] || $res['status'] =='error'){
                return response()->json($res);
            }

            return response()->json([
                'status' =>$res['status'],
                'message' =>$res['message'] ,
                'payload' => $res['payload'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update_personal_info($rq)
    {
        try {
            DB::beginTransaction();
            $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;
            $user_id = Auth::user()->emp_id;

            $attribute = ['id' =>$emp_id];
            $value = [
                'fname'=>$rq->fname,
                'lname'=>$rq->lname,
                'mname'=>$rq->mname,
                'ext'=>$rq->ext,
                'birthday'=>isset($rq->birthdate)?Carbon::createFromFormat('m-d-Y',$rq->birthdate)->format('Y-m-d'):null,
                // 'birthplace'=>$rq->birthplace,
                // 'sex'=>$rq->sex,
                // 'civil_status'=>$rq->civil_status,
                // 'telephone_number'=>$rq->telephone,
                // 'mobile_number'=>$rq->mobile,
                // 'p_email'=>$rq->email,
                // 'citizenship'=>$rq->citizenship,
                // 'height'=>$rq->height,
                // 'weight'=>$rq->weight,
                // 'blood_type'=>$rq->blood_type,
                // 'gsis'=>$rq->gsis,
                'pagibig'=>$rq->pagibig,
                'philhealth'=>$rq->philhealth,
                'sss'=>$rq->sss,
                'tin'=>$rq->tin,
                'is_active'=>0,
                'is_completed'=>0,
                // 'agency'=>$rq->agency
            ];

            $query = Employee::updateOrCreate($attribute,$value);
            if ($query->wasRecentlyCreated) {
                $query->update(['created_by'=>$user_id,]);
            }else{
                $query->update(['updated_by' => $user_id,]);
            }
            DB::commit();
            return [ 'status' => 'success','message'=>'success', 'payload' => Crypt::encrypt($query->id) ];
        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function update_employment_info($rq)
    {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->emp_id);
            $department_id = Crypt::decrypt($rq->department_id);
            $position_id = Crypt::decrypt($rq->position_id);
            // $section_id = isset($rq->section_id) ?Crypt::decrypt($rq->section_id):null;
            // $employment_id = Crypt::decrypt($rq->employment_id);
            // $classification_id = Crypt::decrypt($rq->classification_id);
            // $company_id = Crypt::decrypt($rq->company_id);
            // $location_id = Crypt::decrypt($rq->location_id);

            $query = Employee::find($emp_id);
            if(!$query){
                return [ 'status' => 'error','message'=>'Employee record not found'];
            }

            $attribute = ['emp_id' =>$emp_id];
            $value = [
                'department_id'=>$department_id,
                // 'company_id'=>$company_id,
                // 'company_location_id'=>$location_id,
                // 'section_id'=>$section_id,
                // 'employment_id'=>$employment_id,
                // 'classification_id'=>$classification_id,
                'position_id'=>$position_id,
                'is_active'=> isset($rq->is_active) ? $rq->is_active :1,
                // 'date_employed'=>Carbon::createFromFormat('m-d-Y',$rq->date_employed)->format('Y-m-d'),
                'work_status'=>$rq->work_status,
            ];

            $emp_position = HrisEmployeePosition::updateOrCreate($attribute,$value);
            $query->emp_no = $rq->emp_no;
            $query->is_active = 1;
            $query->is_completed = 1;
            $query->save();

            if ($emp_position->wasRecentlyCreated) {
                $emp_position->update(['created_by'=>$user_id]);
            }else{
                $emp_position->update(['updated_by' => $user_id]);
            }

            if(strtolower($emp_position->department->code) == 'mis')
            {
                EmployeeAccount::createAccount($query,$user_id);
            }

            DB::commit();
            return [ 'status' => 'success','message'=>'Registration is Completed', 'payload' => $rq->emp_no];
        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function validate_fullname(Request $rq)
    {
        try {
            $valid = true;
            $message = '';
            $normalizedfName = strtolower(str_replace(' ', '', $rq->fname));
            $normalizedlName = strtolower(str_replace(' ', '', $rq->lname));
            $normalizedmName = $rq->mname ? strtolower(str_replace(' ', '', $rq->mname)) : null;

            $employee = Employee::whereRaw("REPLACE(LOWER(fname), ' ', '') = ?", [$normalizedfName])
            // ->when($normalizedmName !== null, function ($query) use ($normalizedmName) {
            //     // If middle name is provided, match it
            //     $query->whereRaw("REPLACE(LOWER(mname), ' ', '') = ?", [$normalizedmName]);
            // }, function ($query) {
            //     // If no middle name provided, match NULL or empty
            //     $query->where(function ($q) {
            //         $q->whereNull('mname')->orWhere('mname', '');
            //     });
            // })
            ->whereRaw("REPLACE(LOWER(lname), ' ', '') = ?", [$normalizedlName])
            ->where('is_active', 1)
            ->first();

            if ($employee && $employee->emp_no) {
                $valid = false;
                $message = 'Employee fullname already exists and is fully registered';
            }

            return response()->json(['valid' => $valid, 'message' => $message]);

        }catch(\Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function validate_emp_no(Request $rq)
    {
        try {
            $valid = true;
            $message = '';
            $count = Employee::where('emp_no',$rq->emp_no)
            ->where('is_active', 1)
            ->count();
            if ($count > 0) {
                $valid = false;
                $message = 'Employee number already exists';
            }
            return response()->json(['valid' => $valid, 'message' => $message]);

        }catch(\Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

}
