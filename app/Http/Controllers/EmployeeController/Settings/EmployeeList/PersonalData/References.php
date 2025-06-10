<?php

namespace App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\HrisEmployeeReference;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class References extends Controller
{
    protected $isRegisterEmployee = false;

    public function dt(Request $rq)
    {
        $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;

        $data = HrisEmployeeReference::where([['is_deleted',null],['emp_id',$emp_id]])->orderBy('id', 'ASC') ->get();
        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
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

    public function view($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        return view($components.'employee-references', compact('employee','isRegisterEmployee'))->render();
    }

    public function info(Request $rq)
    {
        try {
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $query = HrisEmployeeReference::find($id);
            $payload = [
                'name'=>$query->name,
                'address'=>$query->address,
                'mobile_number'=>$query->mobile_number,
                'email'=>$query->email,
                'company'=>$query->company,
                'position'=>$query->position,
                'relation_to_reference'=>$query->relation_to_reference,
            ];
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);

        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->id;
            $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            $attribute = [
                'id' =>$id,
                'emp_id' =>$emp_id
            ];
            $value = [
                'name'=>$rq->name,
                'address'=>$rq->address,
                'mobile_number'=>$rq->mobile_number,
                'email'=>$rq->email,
                'company'=>$rq->company,
                'position'=>$rq->position,
                'relation_to_reference'=>$rq->relation_to_reference,
                isset($id)?'updated_by':'created_by' =>$user_id,
            ];

            HrisEmployeeReference::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Education is updated', 'payload'=>'' ];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }

    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisEmployeeReference::find($id);
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();

            $query->save();
            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => HrisEmployeeReference::where([['is_deleted',null],['emp_id',$query->emp_id]])->count()
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
