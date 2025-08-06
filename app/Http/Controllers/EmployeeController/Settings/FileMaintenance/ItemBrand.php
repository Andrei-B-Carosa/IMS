<?php

namespace App\Http\Controllers\EmployeeController\Settings\FileMaintenance;

use App\Http\Controllers\Controller;
use App\Models\ImsItemBrand;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ItemBrand extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = ImsItemBrand::when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where('is_deleted',null)
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            $last_update_at = null;
            if($item->updated_by != null){
                $last_updated_by = optional($item->updated_by_emp)->fullname();
                $last_update_at = Carbon::parse($item->updated_at)->format('m-d-Y');
            }elseif($item->created_by !=null){
                $last_updated_by = optional($item->created_by_emp)->fullname();
                $last_update_at = Carbon::parse($item->created_at)->format('m-d-Y');
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_update_at = $last_update_at;

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


    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            $attribute = ['id'=>$id];
            $values = [
                'name' =>ucwords(strtolower($rq->name)),
                'description' =>$rq->description,
                'is_active' =>$rq->is_active,
            ];
            $query = ImsItemBrand::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id, ]);
            }else{
                $query->update([ 'updated_by' => $user_id, ]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Success']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = ImsItemBrand::find($id);
            $payload = base64_encode(json_encode([
                'name' =>$query->name,
                'description' =>$query->description,
                'is_active' =>$query->is_active,
            ]));
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>$payload]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }


    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->encrypted_id);

            $query = ImsItemBrand::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Success',
                'payload' => ImsItemBrand::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function validate(Request $rq)
    {
        try {
            $valid = true;
            $message = '';
            $normalizedName = strtolower(str_replace(' ', '', $rq->name));
            $availableItems = ImsItemBrand::whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalizedName])
            ->where('is_active', 1)
            ->count();
            if ($availableItems > 0) {
                $valid = false;
                $message = 'This name is already in use';
            }
            return response()->json(['valid' => $valid, 'message' => $message]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }
}
