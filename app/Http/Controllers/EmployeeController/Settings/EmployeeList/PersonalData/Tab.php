<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Tab extends Controller
{
    public function tab(Request $rq)
    {
        try {
            $emp_id = isset($rq->emp_id) ?Crypt::decrypt($rq->emp_id):null;
            $components = 'components.employee-details.';
            $employee = Employee::with('emp_details','emp_account:id,emp_id,username,c_email')->find($emp_id);

            $view = match($rq->tab){
                '1',1=>(new PersonalInformation)->view($rq,$components,$employee),
                '2',2=>(new FamilyBackground)->view($rq,$components,$employee),
                '3',3=>(new EducationalBackground)->view($rq,$components,$employee),
                '4',4=>(new WorkExperience)->view($rq,$components,$employee),
                '5',5=>(new DocumentAttachments)->view($rq,$components,$employee),
                '6',6=>(new References)->view($rq,$components,$employee),
                // '2',2=>self::employment_details($rq,$components,$employee),
                // '8',8=>self::account_security($rq,$components,$employee),
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
}
