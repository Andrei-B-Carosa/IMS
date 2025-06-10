<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\AccountSecurity;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountDetails extends Controller
{
    protected $isRegisterEmployee = false;

    public function view($rq,$components,$account)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        $isSystemAdmin = true;
        $emp_account = $account;
        return view($components.'employee-account', compact('isRegisterEmployee','emp_account','isSystemAdmin'))->render();
    }



    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id = Crypt::decrypt($rq->id);
            $query = EmployeeAccount::where([['emp_id',$id],['is_active',1]])->first();

            if($rq->column == 'password'){
                if($rq->newpassword !== $rq->confirmpassword){
                    return [ 'status' => 'error','message'=>'Passwords do not match', 'payload' => ''];
                }
                $values = Hash::make($rq->newpassword);
            }elseif ($rq->column == 'c_email') {
                $values = $rq->emailaddress;
            } else {
                return [ 'status' => 'error', 'message' => 'Invalid column' ];
            }

            $query->{$rq->column} = $values;
            $query->updated_by = $user_id;
            $query->save();

            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }
}
