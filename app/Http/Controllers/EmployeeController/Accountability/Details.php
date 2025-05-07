<?php

namespace App\Http\Controllers\EmployeeController\Accountability;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ImsAccountability;
use App\Models\ImsAccountabilityIssuedTo;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItem;
use App\Models\ImsItemInventory;
use App\Service\Reusable\Datatable;
use App\Service\Select\ItemOption;
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

        $data = ImsAccountabilityItem::with('item_inventory')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where([['accountability_id',$id],['is_deleted',null]])
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

            $name = $item->item_inventory->name;
            $description = $item->item_inventory->description;
            $item_type = $item->item_inventory->item_type_id;

            if($item_type == 1 || $item_type == 8) {
                $array = json_decode($item->item_inventory->description,true);
                $ram = json_decode($array['ram']);

                $storage = json_decode($array['storage'],true);
                $storage_html = '';
                foreach($storage as $row){
                    $storage_html .= 'Storage: '.$row['description'].'<br>';
                };

                $ram = json_decode($array['ram'],true);
                $ram_html = collect($ram)
                ->groupBy('name')
                ->map(function ($items, $size) {
                    return (count($items) > 1 ? count($items) . 'x' : '') . $size;
                })
                ->implode(', ');

                $gpu = json_decode($array['gpu'],true);
                $gpu_html = '';
                foreach($gpu as $row){
                    if($row['type'] == 'Integrated'){
                        continue;
                    }
                    $gpu_html .= 'GPU: '.$row['description'].'<br>';
                };
            }

            if($item_type == 1){
                $description = '<div class=" fs-6">
                    CPU: '.$array['cpu'].'<br>
                    RAM: '.$ram_html.'<br>
                    '.$storage_html.'
                    OS: '.$array['windows_version'].'<br>
                    '.$gpu_html.'
                    Device Name: '.$array['device_name'].'<br>
                </div>';
            }

            if($item_type == 8){
                $description = '<div class=" fs-6">
                    Model: '.$array['model'].'<br>
                    CPU: '.$array['cpu'].'<br>
                    RAM: '.$ram_html.'<br>
                    '.$storage_html.'
                    OS: '.$array['windows_version'].'<br>
                    Device Name: '.$array['device_name'].'<br>
                    S/N: '.$array['serial_number'].'
                </div>';
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_update_at = $last_update_at;

            $item->name =  $name ?? $description;
            $item->description = $description;
            $item->serial_number = $item->item_inventory->serial_number;
            $item->price = $item->item_inventory->price;
            $item->remarks = $item->remarks;
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

        $data = ImsAccountabilityIssuedTo::with('employee')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where([['accountability_id',$id],['is_deleted',null]])
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

            $issued_at = null;
            if($item->issued_at != null){
                $issued_at = Carbon::parse($item->issued_at)->format('m-d-Y');
            }

            $removed_at = null;
            if($item->removed_at != null){
                $removed_at = Carbon::parse($item->removed_at)->format('m-d-Y');
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

    public function new_accountability(Request $rq)
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
            ]);

            $issued_item = json_decode($rq->issued_item,true);
            $issued_item_array =[];
            foreach($issued_item as $row)
            {
                $item_id = Crypt::decrypt($row['id']);
                $find = ImsItemInventory::find($item_id);
                if(!$find){
                    return false;
                }

                $issued_item_array[]=[
                    'accountability_id' =>$accountability->id,
                    'item_inventory_id' =>$item_id,
                    'status'=>1,
                    'issued_at'=>$issued_at,
                    'remarks'=>''
                ];
                $find->status = 2;
                $find->save();
            }
            ImsAccountabilityItem::insert($issued_item_array);

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


    public function info_issued_items(Request $rq)
    {
        $modal = match($rq->modal_type){
            "1" => $this->modal_system_unit($rq),
            default => $this->modal_other_item($rq)
        };
        $payload = base64_encode($modal);
        return response()->json([
            'status'=>'success',
            'message' => 'success',
            'payload'=>$payload,
        ]);
    }

    public function modal_system_unit($rq)
    {
        $id = Crypt::decrypt($rq->id);

        $query = ImsAccountabilityItem::with('item_inventory')->find($id);
        $query->item_inventory->description = json_decode($query->item_inventory->description,true);

        $array = $query->item_inventory->description;

        $ram = json_decode($array['ram'],true);
        foreach($ram as $row){
            $rq = $rq->merge([
                'id' => null,
                'view'=>'1',
                'type'=>'search_ram',
                'search'=>$row['name']
            ]);
            $ram_options[] =[
                'html' =>(new ItemOption)->list($rq),
                'serial_number'=>$row['serial_number'],
            ];
        };

        $storage = json_decode($array['storage'],true);
        foreach($storage as $row){
            $rq = $rq->merge([
                'id' => null,
                'view'=>'1',
                'type'=>'search_storage',
                'search'=>$row['name']

            ]);
            $storage_options[] =[
                'html' =>(new ItemOption)->list($rq),
                'serial_number'=>$row['serial_number'],
            ];
        };

        $gpu = json_decode($array['gpu'],true);
        foreach($gpu as $row){
            if(strtolower($row['type']) =='integrated'){
                continue;
            }

            $rq = $rq->merge([
                'id' => null,
                'view'=>'1',
                'type'=>'search_gpu',
                'search'=>$row['name']

            ]);
            $gpu_options[] =[
                'html' =>(new ItemOption)->list($rq),
                'serial_number'=>'',
            ];
        };

        return view('employee.pages.accountability.modal.edit_system_unit', compact('query','ram_options','storage_options','gpu_options'))->render();

    }

    public function modal_other_item($rq)
    {
        $id = Crypt::decrypt($rq->id);
        $query = ImsAccountabilityItem::with('item_inventory')->find($id);

        return view('employee.pages.accountability.modal.edit_other_item', compact('query'))->render();
    }

    public function update_issued_items(Request $rq)
    {
        try {
            DB::beginTransaction();

            $response = match($rq->update_type){
                'system unit' => $this->update_system_unit($rq),
                'laptop' => $this->update_laptop($rq),
                'other_item' => $this->update_other_item($rq),
                default => false,
            };

            DB::commit();
            return $response;
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update_system_unit($rq)
    {
        $id = Crypt::decrypt($rq->encrypted_id);
        $query = ImsAccountabilityItem::with('item_inventory')->find($id);

        $array_storage = [];
        $storage = json_decode($rq->storage,true);
        foreach($storage as $row)
        {
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
            $search_id = Crypt::decrypt($row['id']);
            $search = ImsItem::find($search_id);
            $array_gpu[]=[
                'name'=>$search->name,
                'description' =>$search->description,
                'serial_number'=>$row['serial_number'],
                'type' => 'Dedicated',
            ];
        }

        $query->item_inventory->name = $rq->item;
        $query->item_inventory->description = json_encode([
            'cpu'=>$rq->cpu,
            'ram'=>json_encode($array_ram),
            'storage'=>json_encode($array_storage),
            'gpu'=>json_encode($array_gpu),
            'device_name'=>$rq->device_name,
            'os_installed_date'=>$rq->os_installed_date,
            'windows_version'=>$rq->windows_version,
        ]);

        $query->remarks = $rq->remarks;
        $query->updated_by = Auth::user()->emp_id;

        $query->item_inventory->save();
        $query->save();

        return response()->json(['status' => 'success', 'message'=>'Update Success']);
    }

    public function update_laptop($rq)
    {

    }

    public function update_other_item($rq)
    {
        $id = Crypt::decrypt($rq->encrypted_id);
        $user_id = Auth::user()->emp_id;
        $query = ImsAccountabilityItem::with('item_inventory')->find($id);

        $query->item_inventory->name = $rq->item;
        $query->item_inventory->description = $rq->description;
        $query->item_inventory->updated_by = $user_id;

        $query->remarks = $rq->remarks;
        $query->updated_by = $user_id;

        $query->item_inventory->save();
        $query->save();

        return response()->json(['status' => 'success', 'message'=>'Update Success']);
    }

    public function delete_issued_item(Request $rq)
    {
        $id = Crypt::decrypt($rq->encrypted_id);
        $user_id = Auth::user()->emp_id;
        $query = ImsAccountabilityItem::with('item_inventory')->find($id);

        if($query->status == 1 && $query->item_inventory->status == 2){
            $query->status = 2; //Returned
            $query->updated_by = $user_id;

            $query->item_inventory->status = 1 ;  //Available in inventory
            $query->item_inventory->updated_by =$user_id ;  //

            $query->save();
            $query->item_inventory->save();

            return response()->json(['status' => 'success', 'message'=>'Status is updated']);
        }

    }

    public function delete_issued_to(Request $rq)
    {
        $id = Crypt::decrypt($rq->encrypted_id);
        $user_id = Auth::user()->emp_id;
        $query = ImsAccountabilityIssuedTo::find($id);

        if($query->status == 1 ){

            $query->status = 2;
            $query->removed_at = Carbon::now();
            $query->updated_by = $user_id;

            $query->save();

            return response()->json(['status' => 'success', 'message'=>'Status is updated']);
        }

    }

}
