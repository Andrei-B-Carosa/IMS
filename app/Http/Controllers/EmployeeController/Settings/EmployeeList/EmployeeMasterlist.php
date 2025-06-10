<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EmployeeMasterlist extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $data = Employee::with(['emp_details'])
        ->when($filter_status,function($q) use($filter_status){
            $q->where('is_active',$filter_status);
        })
        // ->whereHas('emp_account')
        ->whereHas('emp_details')
        ->where('is_deleted',null)
        ->get();

        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            $last_updated_date = null;
            if($item->updated_by != null){
                $last_updated_by = optional($item->updated_by_emp)->fullname();
                $last_updated_date = Carbon::parse($item->updated_at)->format('m-d-Y');
            }elseif($item->created_by !=null){
                $last_updated_by = optional($item->created_by_emp)->fullname();
                $last_updated_date = Carbon::parse($item->created_at)->format('m-d-Y');
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_updated_date = $last_updated_date;

            $emp_details = $item->emp_details;

            $item->employee_name = $item->fullname();
            $item->emp_no = $item->emp_no;
            $item->department_name = $emp_details->department->name;
            $item->position_name = $emp_details->position->name;
            $item->c_email = $item->emp_account?$item->emp_account->c_email:'';
            $item->date_employed = $emp_details->date_employed?Carbon::parse($emp_details->date_employed)->format('F j, Y'):'No Date Hired';

            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });

        $table = new Datatable($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);
    }

    public function restore(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = Employee::find($id);
            $query->is_active = $rq->is_active;
            $query->updated_by = $user_id;
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message'=>'Employee record is restored',
                'payload' => Employee::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function archive(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = Employee::find($id);
            $query->is_active = $rq->is_active;
            $query->updated_by = $user_id;
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Record is archived',
                'payload' => Employee::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = Employee::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Record is removed',
                'payload' => Employee::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function emp_details(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id =  Crypt::decrypt($rq->id);
            $query = Employee::find($id);

            $payload = [
                'name'=>$query->fullname(),
                'position'=>$query->emp_details->position->name,
                'position'=>$query->emp_details->department->name,
            ];
            return response()->json([
                'status' => 'success',
                'message'=>'success',
                'payload' => base64_encode(json_encode($payload))
            ]);

        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
