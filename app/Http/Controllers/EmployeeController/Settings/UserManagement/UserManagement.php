<?php

namespace App\Http\Controllers\EmployeeController\Settings\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ImsRole;
use App\Models\ImsUserRole;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserManagement extends Controller
{
    public function list(Request $rq)
    {
        try{
            $role_id = $rq->query('role_id') ? Crypt::decrypt($rq->query('role_id')) : false;
            $query = ImsRole::with([
                'role_access',
                'user_roles'=> fn($q) => $q->where('is_active', 1)
            ])
            ->when($role_id, fn($q) => $q->where('id', $role_id))
            ->where('is_active',1)
            ->get();

            $array = [];
            foreach($query as $data)
            {
                $array_role_access = [];
                foreach($data->role_access as $access){
                    $array_file_layer = [];
                    if($role_id){
                        foreach($access->system_file->file_layer as $file_layer){
                            $array_file_layer[]=[
                                'encrypted_id'=>Crypt::encrypt($file_layer->id),
                                'name' => $file_layer->system_layer->name,
                                'status'=>$file_layer->status,
                            ];
                        }
                    }

                    $array_role_access[]=[
                        'encrypted_id'=>Crypt::encrypt($access->id),
                        'name'=>$access->system_file->name,
                        'file_layer'=>$array_file_layer,
                        'status' =>$access->is_active
                    ];
                }
                $array[]=[
                    'encrypted_id'=>Crypt::encrypt($data->id),
                    'role'=>$data->name,
                    'access'=> $array_role_access,
                    'user_roles_count' =>$data->user_roles->count(),
                ];
            }

            $payload = base64_encode(json_encode($array));
            return response()->json([
                'status' =>'success',
                'message' =>'success',
                'payload' => $payload,
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->id);
            $role_id = Crypt::decrypt($rq->role_id);

            $attribute = [
                'emp_id'=>$emp_id,
                'role_id'=>$role_id,
            ];
            $values = ['is_active' =>$rq->is_active];

            $query = ImsUserRole::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id ]);
                $message = 'Added successfully';
            }else{
                $query->update([ 'updated_by' => $user_id ]);
                $message = 'Details is updated';
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>$message]);
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

            $emp_id =  Crypt::decrypt($rq->emp_id);
            $role_id =  Crypt::decrypt($rq->role_id);

            $query = ImsUserRole::where([['role_id',$role_id],['emp_id',$emp_id],['is_active',1]])->first();
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => ImsUserRole::where([['role_id',$query->role_id],['is_active',1]])->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function employee_list(Request $rq)
    {
        $role_id = Crypt::decrypt($rq->role_id);
        $data = Employee::where([['is_deleted',null],['is_active',1]])
        ->whereDoesntHave('user_roles', function ($q) use($role_id) {
            $q->where([['is_active', 1],['role_id',$role_id]]);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;

            $employee_details = $item->emp_details;

            $item->emp_name = $item->fullname();
            $item->emp_no = $item->emp_no;
            $item->emp_department = $employee_details->department->name;
            $item->emp_position = $employee_details->position->name;

            $item->position = $employee_details->position->name;
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

    public function user_list(Request $rq)
    {
        $role_id = Crypt::decrypt($rq->role_id);
        $data = Employee::where([['is_deleted',null],['is_active',1]])
        ->whereHas('user_roles', function ($q) use ($role_id) {
            $q->where('is_active', 1)
              ->where('role_id', $role_id);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;

            $employee_details = $item->emp_details;

            $item->emp_name = $item->fullname();
            $item->emp_no = $item->emp_no;
            $item->emp_department = $employee_details->department->name;
            $item->emp_position = $employee_details->position->name;

            $item->position = $employee_details->position->name;
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

    // public function update_system_file(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $user_id = Auth::user()->emp_id;
    //         $file_id = Crypt::decrypt($rq->file_id);
    //         $role_id = Crypt::decrypt($rq->role_id);

    //         ImsRoleAccess::where([['file_id',$file_id],['role_id',$role_id]])->update([
    //             'is_active'=>$rq->status,
    //             'updated_by' =>$user_id
    //         ]);

    //         DB::commit();
    //         return response()->json(['status' => 'success', 'message'=>'Updated Successfully']);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    // public function update_file_layer(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $user_id = Auth::user()->emp_id;
    //         $layer_id = Crypt::decrypt($rq->layer_id);
    //         $role_id = Crypt::decrypt($rq->role_id);

    //         $query = ImsSystemFileLayer::find($layer_id);
    //         $query->status = $rq->status;
    //         $query->updated_by = $user_id;
    //         $query->save();

    //         DB::commit();
    //         return response()->json(['status' => 'success', 'message'=>'Updated Successfully']);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }
}
