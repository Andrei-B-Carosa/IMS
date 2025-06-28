<?php

namespace App\Http\Controllers\EmployeeController\Accountability;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ImsAccountability;
use App\Models\ImsAccountabilityIssuedTo;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItemInventory;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Lists extends Controller
{
    public function list(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $page = $rq->page;
        $search = $rq->search;
        $perPage = 12;

        $query = ImsAccountability::with([
            // 'issued_to' => function ($q) {
            //     $q->where('status', 1);
            // },
            // 'accountability_item' => function ($q) {
            //     $q->where('status', 1);
            // },
            'issued_by_emp'
        ])
        ->when($filter_status,function($q) use($filter_status){
            // $filter_status = $filter_status == 'active'?1:2;
            $q->where('status',$filter_status);
        })
        ->when($search, function($q) use($search) {
            $q->where('form_no', 'like', "%$search%")
              ->orWhereHas('issued_to.employee', function($q) use($search) {
                  $q->whereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%$search%"]);
              });
        })
        ->where('is_deleted',null)->paginate($perPage,['*'], 'page', $page);

        $data = [];
        if(!empty($query)){
            foreach($query as $item)
            {
                $last_updated_by = null;
                $last_updated_at = null;
                if($item->updated_by != null){
                    $last_updated_by = optional($item->updated_by_emp)->fullname();
                    $last_updated_at = Carbon::parse($item->updated_at)->format('m-d-Y');
                }elseif($item->created_by !=null){
                    $last_updated_by = optional($item->created_by_emp)->fullname();
                    $last_updated_at = Carbon::parse($item->created_at)->format('m-d-Y');
                }

                $issued_to = [];
                foreach($item->issued_to as $row)
                {
                    if($row->status ==2){  continue; }
                    $issued_to[] =
                    [
                        'id'=>Crypt::encrypt($row->emp_id),
                        'fullname'=>$row->employee->fullname(),
                    ];
                }

                $issued_item = [];
                foreach($item->accountability_item as $row)
                {
                    $item_inventory = $row->item_inventory;
                    $issued_item[] = [
                        'id'=>Crypt::encrypt($row->item_inventory_id),
                        'name'=>$item_inventory->name,
                        'description'=>$item_inventory->description,
                    ];
                }

                $data[]=[
                    'form_no' => $item->form_no,
                    'status' => $item->status,
                    'issued_at' => $item->issued_at?Carbon::parse($item->issued_at)->format('m-d-Y') : '--',
                    'issued_to' => $issued_to,
                    'issued_item'=> $issued_item,
                    'issued_by' => $item->issued_by_emp->fullname(),
                    'last_updated_by' => $last_updated_by,
                    'last_updated_at' => $last_updated_at,
                    'encrypted_id' => Crypt::encrypt($item->id),
                ];
            }
        }

        return [
            'status'=>'success',
            'message' => 'success',
            'payload' => base64_encode(json_encode([
                'data' => $data,
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'last_page' => $query->lastPage(),
                    'total' => $query->total(),
                    'per_page' => $query->perPage()
                ]
            ]))
        ];
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            $received_by = Crypt::decrypt($rq->received_by);
            $issued_by = Auth::user()->emp_id;
            $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');

            $accountability = ImsAccountability::create([
                'form_no'=>$rq->form_no,
                'issued_at'=>$issued_at,
                'issued_by'=>$issued_by,
                'received_by'=>$received_by,
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

}
