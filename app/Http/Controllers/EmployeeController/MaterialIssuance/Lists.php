<?php

namespace App\Http\Controllers\EmployeeController\MaterialIssuance;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ImsItemInventory;
use App\Models\ImsMaterialIssuance;
use App\Models\ImsMaterialIssuanceIssuedTo;
use App\Models\ImsMaterialIssuanceItem;
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
        $perPage = 10;

        $query = ImsMaterialIssuance::with([
            'issued_by_emp',
            'issued_item'
        ])
        ->when($filter_status,function($q) use($filter_status){
            // $filter_status = $filter_status == 'complete'?1:2;
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

                $issued_item = self::process_list_issued_item($item);
                $issued_to = self::process_list_issued_to($item);

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

    public function process_list_issued_item($item)
    {
        $issued_item_grouped = [];

        $grouped = collect($item->issued_item)
        ->groupBy(function ($row) {
            return strtolower(trim($row->item_inventory->name));
        });

        foreach ($grouped as $group) {
            $firstItem = $group->first();
            $issued_item_grouped[] = [
                'name' => $firstItem->item_inventory->name . ' (' . $group->count() . 'X)',
                'description' => $firstItem->item_inventory->name . ' (' . $group->count() . 'X)',
                'id' => Crypt::encrypt($firstItem->item_inventory_id),
            ];
        }

        return $issued_item_grouped;
    }

    public function process_list_issued_to($item)
    {
        $issued_to = [];
        foreach($item->issued_to as $row)
        {
            $issued_to[] =
            [
                'id'=>Crypt::encrypt($row->emp_id),
                'fullname'=>$row->employee->fullname(),
            ];
        }

        return $issued_to;
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            $received_by = Crypt::decrypt($rq->received_by);
            $issued_by = Auth::user()->emp_id;
            $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');

            $material_issuance = ImsMaterialIssuance::create([
                'form_no'=>$rq->form_no,
                'mrs_no'=>$rq->mrs_no,
                'issued_at'=>$issued_at,
                'issued_by'=>$issued_by,
                'received_by'=>$received_by,
                'remarks'=>$rq->remarks,
                'status'=>$rq->status,
                'created_by'=>$issued_by,
            ]);

            $update_issued_item = self::update_issued_item($rq,$material_issuance);
            if(!$update_issued_item){
                return response()->json(['status' => 'error', 'message'=>'Error processing the items']);
            }
            $update_issued_to = self::update_issued_to($rq,$material_issuance);
            if(!$update_issued_to){
                return response()->json(['status' => 'error', 'message'=>'Error processing the issued to']);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Material Issuance is saved']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update_issued_item($rq,$query)
    {
        $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
        $issued_item = json_decode($rq->issued_item,true);
        $issued_item_array =[];
        foreach($issued_item as $row)
        {
            $id = Crypt::decrypt($row['id']);
            $find = ImsItemInventory::find($id);
            if(!$find){
                return false;
            }

            $normalizedName = strtolower(str_replace(' ', '', $find->name));
            $availableItems = ImsItemInventory::whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalizedName])
            ->where('status', 1)
            ->limit($row['quantity'])
            ->pluck('id');
            if ($availableItems->count() < $row['quantity']) {
                return false;
            }

            foreach($availableItems as $item_id){
                $issued_item_array[] = [
                    'material_issuance_id' =>$query->id,
                    'item_inventory_id' =>$item_id,
                    'status'=>1,
                    'issued_at'=>$issued_at,
                    'remarks'=>''
                ];
            }
        }

        if (empty($issued_item_array)) {
            return false;
        }
        ImsMaterialIssuanceItem::insert($issued_item_array);
        return true;
    }

    public function update_issued_to($rq,$query)
    {
        $issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
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
                'material_issuance_id' => $query->id,
                'emp_id'=>$emp_id,
                'department_id'=>$emp_details->department_id,
                'position_id'=>$emp_details->position_id,
                'status'=>1,
                'issued_at'=>$issued_at,
            ];
        }

        if (empty($issued_to_array)) {
            return false;
        }

        ImsMaterialIssuanceIssuedTo::insert($issued_to_array);
        return true;
    }

    public function check_item_quantity(Request $rq)
    {
        try {
            $valid = true;
            $message = "Quantity is sufficient";

            $item_id = isset($rq->item_id) && $rq->item_id != "undefined"? Crypt::decrypt($rq->item_id): false;
            $find = ImsItemInventory::find($item_id);

            $normalizedName = strtolower(str_replace(' ', '', $find->name));
            $availableItems = ImsItemInventory::whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalizedName])
            ->where('status', 1)
            ->limit($rq->quantity)
            ->pluck('id');

            if ($availableItems->count() < $rq->quantity) {
                $valid = false;
                $message = 'Quantity is insufficient';
            }

            return response()->json(['valid' => $valid, 'message'=>$message]);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }
}
