<?php

namespace App\Http\Controllers\EmployeeController\MaterialIssuance;

use App\Http\Controllers\Controller;
use App\Models\ImsItemInventory;
use App\Models\ImsMaterialIssuance;
use App\Models\ImsMaterialIssuanceIssuedTo;
use App\Models\ImsMaterialIssuanceItem;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Details extends Controller
{
    public function dt_issued_items(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $id = Crypt::decrypt($rq->id);

        $data = ImsMaterialIssuanceItem::with('item_inventory')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where([['material_issuance_id',$id],['is_deleted',null]])
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

            $issued_at = null;
            if($item->issued_at != null){
                $issued_at = Carbon::parse($item->issued_at)->format('M d, Y');
            }

            $removed_at = null;
            if($item->removed_at != null){
                $removed_at = Carbon::parse($item->removed_at)->format('M d, Y');
            }

            $name = $item->item_inventory->name;
            $description = $item->item_inventory->description;
            $item_type = $item->item_inventory->item_type_id;

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_update_at = $last_update_at;

            $item->issued_at = $issued_at;
            $item->removed_at = $removed_at;

            $item->name =  $name ?? $description;
            $item->description = $description;
            $item->serial_number = $item->item_inventory->serial_number;
            $item->price = $item->item_inventory->price;
            $item->type = $item_type;
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

    public function dt_issued_to(Request $rq)
    {
        $id = Crypt::decrypt($rq->id);
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = ImsMaterialIssuanceIssuedTo::with('employee')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where([['material_issuance_id',$id],['is_deleted',null]])
        ->get();

        $data->transform(function ($item, $key) {

            $allowed_edit = $item->ims_material_issuance->status ==1 ? false:true;

            $last_updated_by = null;
            $last_updated_date = null;

            if($item->updated_by != null){
                $last_updated_by = optional($item->updated_by_emp)->fullname();
                $last_updated_date = Carbon::parse($item->updated_at)->format('m-d-Y');
            }elseif($item->created_by !=null){
                $last_updated_by = optional($item->created_by_emp)->fullname();
                $last_updated_date = Carbon::parse($item->created_at)->format('m-d-Y');
            }

            $issued_at = null;
            if($item->issued_at != null){
                $issued_at = Carbon::parse($item->issued_at)->format('M d, Y');
            }

            $removed_at = null;
            if($item->removed_at != null){
                $removed_at = Carbon::parse($item->removed_at)->format('M d, Y');
            }


            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_updated_date = $last_updated_date;

            $item->issued_at = $issued_at;
            $item->removed_at = $removed_at;

            $emp = $item->employee;
            $emp_details = $emp->emp_details;

            $item->name = $emp->fullname();
            $item->emp_no = $emp->emp_no;
            $item->department = $emp_details->department->name;
            $item->position = $emp_details->position->name;
            $item->allowed_edit = $allowed_edit;
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

    public function dt_available_items(Request $rq)
    {
        $data = ImsItemInventory::where([['status',1],['is_deleted',null]])
        ->whereHas('item_type', function ($q) {
            $q->where('display_to', 2)->orWhereNull('display_to');
        })
        ->get();

        $data->transform(function ($item, $key) {

            $name = $item->name;
            $description = $item->description;
            $item_type = $item->item_type_id;

            $item->count = $key + 1;
            $item->name =  $name ?? $description;
            $item->description = $description;
            $item->serial_number = $item->serial_number;
            $item->price = $item->price;
            $item->type = $item_type;
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

    public function info_material_issuance(Request $rq)
    {
        try {

            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsMaterialIssuance::with(['received_by_emp','issued_by_emp'])->find($id);

            $array = [];
            if($query){
                $array= [
                    'form_no'=>$query->form_no,
                    'mrs_no'=>$query->mrs_no,
                    'date_issued'=>Carbon::parse($query->issued_at)->format('m-d-Y'),
                    'accountability_status'=>$query->status,
                    'received_by'=>$query->received_by,
                    'remarks'=>$query->remarks,
                    'status'=>$query->status,
                    'received_by'=>$query->received_by_emp->fullname(),
                    'issued_by'=>$query->issued_by_emp->fullname(),
                ];
            }

            $payload = base64_encode(json_encode($array));
            return response()->json(['status' => 'success', 'message'=>'Success','payload'=>$payload]);
            }catch(Exception $e){
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage(),
                ]);
            }
    }

    public function update_material_issuance(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsMaterialIssuance::find($id);

            $query->form_no = $rq->form_no;
            $query->mrs_no = $rq->mrs_no;
            $query->issued_at = Carbon::createFromFormat('m-d-Y', $rq->date_issued)->format('Y-m-d');
            $query->issued_by = Crypt::decrypt($rq->issued_by);
            $query->received_by = Crypt::decrypt($rq->received_by);
            $query->updated_by = $user_id;
            $query->status = $rq->status;
            $query->remarks = $rq->remarks;
            $query->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Success', 'payload'=>'reload']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update_material_issuance_item(Request $rq)
    {
        try {
            DB::beginTransaction();

            $material_issuance_id = Crypt::decrypt($rq->material_issuance_id);
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            ImsMaterialIssuanceItem::insert([
                'material_issuance_id' =>$material_issuance_id,
                'item_inventory_id' =>$id,
                'status'=>$rq->status,
                'issued_at'=>Carbon::now(),
                'remarks'=>$rq->remarks,
                'created_by'=>$user_id,
            ]);

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

    public function remove_issued_item(Request $rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsMaterialIssuanceItem::find($id);

            $query->remarks = $rq->remarks;
            $query->status = $rq->status;
            $query->removed_at = Carbon::now();
            $query->updated_by = $user_id;

            $query->save();

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
}
