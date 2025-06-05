<?php

namespace App\Http\Controllers\EmployeeController\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ImsItem;
use App\Models\ImsItemBrand;
use App\Models\ImsItemInventory;
use App\Models\ImsItemInventoryLog;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Details extends Controller
{
    public function dt_item_logs(Request $rq)
    {
        $id = Crypt::decrypt($rq->id);
        $data = ImsItemInventoryLog::with('employee')->where('item_inventory_id',$id)->get();

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

            $item->emp_fullname = $item->employee->fullname();

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

    public function update_general_details(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsItemInventory::find($id);

            $query->name = $rq->name;

            $item_brand_id = $rq->item_brand? Crypt::decrypt($rq->item_brand):null;
            $item_type_id = $rq->item_type? Crypt::decrypt($rq->item_type):null;

            if(isset($rq->description) && ($query->item_type_id != 1 && $query->item_type_id !=8)){
                $query->description = $rq->description;
            }

            if(isset($rq->status)){
                $query->status = $rq->status;
            }

            if($query->item_type_id == 1 || $query->item_type_id == 8){
                $description = json_decode($query->description,true);
                if($query->item_brand_id != $item_brand_id){
                    $description['brand'] = ImsItemBrand::find($item_brand_id)->value('name');
                }
                if($query->serial_number != $rq->serial_number){
                    $description['serial_number'] = $rq->serial_number;
                }
                $query->description = json_encode($description);
            }

            $query->serial_number = $rq->serial_number;
            $query->price = $rq->price;

            $query->item_brand_id = $item_brand_id;
            $query->item_type_id = $item_type_id;
            $query->supplier_id = $rq->supplier? Crypt::decrypt($rq->supplier):null;

            $query->received_at = $rq->received_at ? Carbon::createFromFormat('m-d-Y', $rq->received_at)->format('Y-m-d') : null;
            $query->received_by = $rq->received_by? Crypt::decrypt($rq->received_by):null;

            $query->updated_by = Auth::user()->emp_id;
            $query->remarks = $rq->remarks;

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

    public function update_item_details(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsItemInventory::find($id);

            $array_storage = [];
            $storage = json_decode($rq->storage,true);
            foreach($storage as $row)
            {
                if(empty($row['id'])){
                    continue;
                }
                $search_id = Crypt::decrypt($row['id']);
                $search = ImsItem::find($search_id);
                $array_storage[]=[
                    'name'=>$search->name,
                    'description' =>$search->description,
                    'type' =>$search->item_type->name,
                    'serial_number'=>$row['serial_number'],
                ];
            }

            $array_ram = [];
            $ram = json_decode($rq->ram,true);
            foreach($ram as $row)
            {
                if(empty($row['id'])){
                    continue;
                }
                $search_id = Crypt::decrypt($row['id']);
                $search = ImsItem::find($search_id);

                $array_ram[]=[
                    'name'=>$search->name,
                    'description' =>$search->description,
                    'serial_number'=>$row['serial_number'],
                ];
            }

            $array_gpu = [];
            $gpu = json_decode($rq->gpu,true);
            foreach($gpu as $row){
                if(empty($row['id'])){
                    continue;
                }
                $search_id = Crypt::decrypt($row['id']);
                $search = ImsItem::find($search_id);
                $array_gpu[]=[
                    'name'=>$search->name,
                    'description' =>$search->description,
                    'serial_number'=>$row['serial_number'],
                    'type' => 'Dedicated',
                ];
            }

            $description = [
                'cpu'=>$rq->cpu,
                'ram'=>json_encode($array_ram),
                'storage'=>json_encode($array_storage),
                'gpu'=>json_encode($array_gpu),
                'device_name'=>$rq->device_name,
                'os_installed_date'=>$rq->os_installed_date ? Carbon::createFromFormat('m-d-Y', $rq->os_installed_date)->format('Y-m-d') : null,
                'windows_version'=>$rq->windows_version,
            ];

            if($query->item_type_id == 8){
                $description['model']=$rq->laptop_model;
                // $description['brand']=$query->item_brand->name;
                // $description['serial_number']=$query->serial_number;
            }

            $query->description = $description;
            $query->updated_by = Auth::user()->emp_id;
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
