<?php

namespace App\Http\Controllers\EmployeeController\Accountability;

use App\Http\Controllers\Controller;
use App\Models\ImsAccountability;
use App\Models\ImsAccountabilityIssuedTo;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Lists extends Controller
{
    public function list(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $page = $rq->page;
        $perPage = 10;

        $query = ImsAccountability::with(['issued_to','accountability_item','issued_by_emp'])
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where('is_deleted',null)->paginate($perPage,['*'], 'page', $page);

        $data = $this->process_accountability_list($query);

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

    public function process_accountability_list($query)
    {
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

        return $data;
    }

    public function fetch_accountability(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = ImsAccountability::find($id)->get();
            $array =[];

            if($query){
                $issued_to = [];
                foreach($query->issued_to as $row){
                    $issued_to_array[]=[
                        'id'=>Crypt::encrypt($row->emp_id),
                        'emp_no'=>$row->employee->emp_no,
                        'fullname'=>$row->employee->fullname(),
                        'department'=>$row->employee->emp_details->department->name,
                        'position'=>$row->employee->emp_details->position->name,
                        'status'=>$row->status
                    ];
                }
                $issued_item = [];
                foreach($query->accountability_item as $row)
                {
                    $item_inventory = $row->item_inventory;
                    $issued_item[] = [
                        'id'=>Crypt::encrypt($row->item_inventory_id),
                        'name'=>$item_inventory->name,
                        'description'=>$item_inventory->description,
                        'serial_number'=>$item_inventory->serial_number,
                        'price'=>$item_inventory->price,
                        'remarks'=>$item_inventory->remarks,
                    ];
                }
            }

            $payload = base64_encode(json_encode($array));
            return response()->json([
                'status'=>'success',
                'message' => 'success',
                'payload'=>$payload,
            ]);
        } catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' =>  $e->getMessage(),
                // 'message' =>  'Something went wrong. try again later',
            ]);
        }
    }

    public function fetch_item(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            dd($id);
        } catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' =>  $e->getMessage(),
                // 'message' =>  'Something went wrong. try again later',
            ]);
        }
    }
}
