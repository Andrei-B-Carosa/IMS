<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use App\Models\HrisEmployeeDocument;
use App\Models\HrisEmployeeEducation;
use App\Models\HrisEmployeePosition;
use App\Service\Select\ClassificationOptions;
use App\Service\Select\CompanyLocationOptions;
use App\Service\Select\CompanyOptions;
use App\Service\Select\DepartmentOptions;
use App\Service\Select\DocumentTypeOptions;
use App\Service\Select\EmploymentTypeOptions;
use App\Service\Select\PositionOptions;
use App\Service\Select\SectionOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeDetails extends Controller
{
    protected $isRegisterEmployee = false;

    public function tab(Request $rq)
    {
        try {
            $emp_id = isset($rq->emp_id) ?Crypt::decrypt($rq->emp_id):null;
            $components = 'components.employee-details.';
            $employee = Employee::with('emp_details','emp_account:id,emp_id,username,c_email')->find($emp_id);

            $view = match($rq->tab){
                'personal_data'=>self::view_personal_data($rq,$components,$employee),
                'employment_details'=>self::view_employment_details($rq,$components,$employee),
                'account_security'=>self::view_account_security($rq,$components,$employee),
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

    public function view_personal_data($rq)
    {
        return view('employee.pages.settings.employee_list.employee_details.'.$rq->tab)->render();
    }

    public function view_employment_details($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
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

        return view('employee.pages.settings.employee_list.employee_details.employment_details', compact('employee','isRegisterEmployee','options'))->render();

    }

    public function view_account_security($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        $isSystemAdmin = true;
        $emp_account = $employee->emp_account;
        return view('employee.pages.settings.employee_list.employee_details.'.$rq->tab,compact('employee','isRegisterEmployee','emp_account','isSystemAdmin'))->render();
    }


}
