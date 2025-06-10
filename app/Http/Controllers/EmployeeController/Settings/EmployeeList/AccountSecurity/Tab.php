<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\AccountSecurity;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Tab extends Controller
{
    public function tab(Request $rq)
    {
        try {
            $emp_id = isset($rq->emp_id) ?Crypt::decrypt($rq->emp_id):null;
            $components = 'components.employee-details.';
            $account = EmployeeAccount::where('emp_id',$emp_id)->first();

            $view = match($rq->tab){
                '1',1=>(new AccountDetails)->view($rq,$components,$account),
                // '2',2=>(new FamilyBackground)->view($rq,$components,$employee),
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
