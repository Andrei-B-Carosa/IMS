<?php

namespace App\Http\Controllers\EmployeeController\Accountability;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ImsAccountability;
use App\Models\ImsAccountabilityIssuedTo;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItemInventory;
use App\Models\ImsStoredProcedure;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Lists extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status && $rq->filter_status != 'all' ? $rq->filter_status : false;
        $data = ImsStoredProcedure::sp_get_accountability_list($filter_status);
        $data->transform(function ($item, $key) {
            $issued_at = Carbon::parse($item->a_issued_at)->format('M d, Y') ?? '--';
            $returned_at = isset($item->a_returned_at)?Carbon::parse($item->a_returned_at)->format('M d, Y') : '--';
            $item->count = $key + 1;
            $item->issued_at =  $issued_at;
            $item->returned_at =  $returned_at;
            $item->tag_number =  $item->item_tag_number_html;
            $item->status =  $item->a_status;
            $item->issued_by = $item->issued_by_name;
            $item->issued_to = $item->accountability_issued_to_html;
            $item->issued_items = $item->accountability_items_html;

            $item->encrypted_id = Crypt::encrypt($item->a_id);

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
        try {
            DB::beginTransaction();

            // $received_by = Crypt::decrypt($rq->received_by);
            $issued_by = Auth::user()->emp_id;
            $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');

            $accountability = ImsAccountability::create([
                'form_no'=>$rq->form_no,
                'issued_at'=>$issued_at,
                'issued_by'=>$issued_by,
                // 'received_by'=>$received_by,
                'remarks'=>$rq->remarks,
                'created_by'=>$issued_by,
            ]);

            $issued_to = json_decode($rq->issued_to,true);
            $issued_to_array =[];
            foreach($issued_to as $row)
            {
                $emp_id = Crypt::decrypt($row['id']);
                $find_emp_id = Employee::find($emp_id);
                $emp_details = $find_emp_id->emp_details;

                if(!$find_emp_id || !$emp_details)
                {
                    return false;
                }

                $issued_to_array[] = [
                    'accountability_id' => $accountability->id,
                    'emp_id'=>$emp_id,
                    'department_id'=>$emp_details->department_id,
                    'position_id'=>$emp_details->position_id,
                    'status'=>1,
                    'issued_at'=>$issued_at,
                ];
            }
            ImsAccountabilityIssuedTo::insert($issued_to_array);

            $issued_item = json_decode($rq->issued_item,true);
            $issued_item_array =[];
            foreach($issued_item as $row)
            {
                $item_id = Crypt::decrypt($row['id']);
                // $find = ImsItemInventory::find($item_id);
                // if(!$find){
                //     return false;
                // }

                $issued_item_array[]=[
                    'accountability_id' =>$accountability->id,
                    'item_inventory_id' =>$item_id,
                    'status'=>1,
                    'issued_at'=>$issued_at,
                    'remarks'=>null
                ];
                // $find->status = 2;
                // $find->save();
            }
            ImsAccountabilityItem::insert($issued_item_array);

            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Accountability is saved']);
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
            $id =  Crypt::decrypt($rq->encrypted_id);

            $query = ImsAccountability::find($id);
            $query->is_deleted = 1;
            $query->remarks = $query->remarks.', Reason for deletion: '.$rq->remarks;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Accountability is removed',
                'payload' => ImsItemInventory::where('is_deleted',null)->count()
            ]);
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
            $id =  Crypt::decrypt($rq->id);
            $query = ImsAccountability::find($id);
            $array = [];
            if($query){
                $temp_arr = [];
                foreach($query->active_accountability_item as $items){
                    $temp_arr['items'][] = $items->item_inventory->name;
                }
                foreach($query->issued_to as $issued_to){
                    $temp_arr['issued_to'][] = $issued_to->employee->fullname();
                }
                $array = [
                    'form_no'=>$query->form_no,
                    'issued_at'=>$query->issued_at,
                    'issued_to'=>implode(',',$temp_arr['issued_to']),
                    'items'=>$temp_arr['items'],
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

    public function transfer(Request $rq)
    {
        try{
            DB::beginTransaction();

            // $id =  Crypt::decrypt($rq->id);
            // $query = ImsAccountability::with(['active_accountability_item'])->find($id);

            // $issued_by = Auth::user()->emp_id;
            // $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
            // $received_by = Crypt::decrypt($rq->received_by);

            // $accountability = ImsAccountability::create([
            //     'form_no'=>$rq->form_no,
            //     'issued_at'=>$issued_at,
            //     'issued_by'=>$issued_by,
            //     'received_by'=>$received_by,
            //     'remarks'=>$rq->remarks,
            //     'created_by'=>$issued_by,
            // ]);

            // $received_by_emp = Employee::find($received_by);
            // $issued_to_array = [
            //     'accountability_id' => $accountability->id,
            //     'emp_id'=>$received_by_emp->id,
            //     'department_id'=>$received_by_emp->department_id,
            //     'position_id'=>$received_by_emp->position_id,
            //     'status'=>1,
            //     'issued_at'=>$issued_at,
            // ];
            // ImsAccountabilityIssuedTo::create($issued_to_array);

            // $transfer_item=[];
            // foreach($query->active_accountability_item as $item){
            //     $transfer_item[]=[
            //         'accountability_id' =>$accountability->id,
            //         'item_inventory_id' =>$item->item_inventory_id,
            //         'status'=>$item->status,
            //         'issued_at'=>$issued_at,
            //         'remarks'=>'Quick transfer to new accountability',
            //     ];
            // }
            // ImsAccountabilityItem::insert($transfer_item);


            $id = Crypt::decrypt($rq->id);
            $issued_by = Auth::user()->emp_id;
            $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
            // $returned_at = Carbon::createFromFormat('m-d-Y', $rq->returned_at)->format('Y-m-d');
            $received_by = Crypt::decrypt($rq->received_by);

            $currentAccountability = ImsAccountability::with('active_accountability_item')->findOrFail($id);
            $activeItems = $currentAccountability->active_accountability_item->toArray();
            $currentAccountability->update(['status' => 2, 'updated_by'=>$issued_by, 'returned_at'=>Carbon::now()]);

            $newAccountability = ImsAccountability::create([
                'form_no'     => $rq->form_no,
                'issued_at'   => $issued_at,
                'issued_by'   => $issued_by,
                'received_by' => $received_by,
                'remarks'     => $rq->remarks,
                'created_by'  => $issued_by,
            ]);

            $received_by_emp = Employee::findOrFail($received_by);
            ImsAccountabilityIssuedTo::create([
                'accountability_id' => $newAccountability->id,
                'emp_id'            => $received_by_emp->id,
                'department_id'     => $received_by_emp->department_id,
                'position_id'       => $received_by_emp->position_id,
                'issued_at'         => $issued_at,
                'remarks'           => 'Quick transfer to new accountability',
            ]);

            $transferItems = [];
            foreach ($activeItems as $item) {
                $transferItems[] = [
                    'accountability_id' => $newAccountability->id,
                    'item_inventory_id' => $item['item_inventory_id'],
                    'status'            => $item['status'],
                    'issued_at'         => $issued_at,
                    'remarks'           => 'Quick transfer to new accountability',
                ];
            }

            if (!empty($transferItems)) {
                ImsAccountabilityItem::insert($transferItems);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Success']);

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

    public function update_accountability($query)
    {

    }

}
