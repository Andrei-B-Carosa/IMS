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
        ->whereHas('item_type',function($q){
            $q->where('display_to',1);
        })->get();

        $data->transform(function ($item, $key) {

            $name = $item->name;
            $description = $item->description;
            $item_type = $item->item_type_id;

            if($item_type == 1 || $item_type == 8) {
                $array = json_decode($item->description,true);
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

    public function dt_available_personnel(Request $rq)
    {
        // $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $accountability_id = Crypt::decrypt($rq->id);

        $data = Employee::with(['emp_details'])
        ->whereHas('emp_details')
        ->whereDoesntHave('ims_accountable_to', function ($q) use ($accountability_id) {
            $q->where([['accountability_id', $accountability_id],['status',1]]);
        })
        ->where([['is_deleted',null],['is_active',1]])
        ->get();

        $data->transform(function ($item, $key) {

            $item->count = $key + 1;

            $emp_details = $item->emp_details;

            $item->employee_name = $item->fullname();
            $item->emp_no = $item->emp_no;
            $item->department_name = $emp_details->department->name;
            $item->position_name = $emp_details->position->name;
            $item->c_email = $item->emp_account->c_email;
            $item->date_employed = $emp_details->date_employed?Carbon::parse($emp_details->date_employed)->format('F j, Y'):'No Date Hired';

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

    public function info_accountability(Request $rq)
    {
        try {

        $id = Crypt::decrypt($rq->encrypted_id);
        $query = ImsAccountability::with(['received_by_emp','issued_by_emp'])->find($id);

        $array = [];
        if($query){
            $array= [
                'form_no'=>$query->form_no,
                'date_issued'=>Carbon::parse($query->issued_at)->format('m-d-Y'),
                'accountability_status'=>$query->status,
                'received_by'=>$query->received_by,
                'remarks'=>$query->remarks,
                'received_by'=>$query->received_by_emp->fullname(),
                'issued_by'=>$query->issued_by_emp->fullname(),
            ];
        }

        $payload = base64_encode(json_encode($array));
        return response()->json(['status' => 'success', 'message'=>'Success','payload'=>$payload]);
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
        try {

            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsAccountabilityItem::with('item_inventory')->find($id);
            $item = $query->item_inventory;
            $description = $item->description;

            if($item->item_type_id == 1 || $item->item_type_id == 8){
                $description_array = json_decode($description,true);
                $ram = json_decode($description_array['ram']);

                $storage = json_decode($description_array['storage'],true);
                $storage_html = '';
                foreach($storage as $row){
                    $storage_html .= 'Storage: '.$row['description'].'<br>';
                };

                $ram = json_decode($description_array['ram'],true);
                $ram_html = collect($ram)
                ->groupBy('name')
                ->map(function ($items, $size) {
                    return (count($items) > 1 ? count($items) . 'x' : '') . $size;
                })
                ->implode(', ');

                $gpu = json_decode($description_array['gpu'],true);
                $gpu_html = '';
                foreach($gpu as $row){
                    if($row['type'] == 'Integrated'){
                        continue;
                    }
                    $gpu_html .= 'GPU: '.$row['description'].'<br>';
                };

                $description = '<div class="fs-6">'
                . ($item->item_type_id == 8 ? 'Model: ' . $description_array['model'] . '<br>' : '')
                . 'CPU: ' . $description_array['cpu'] . '<br>'
                . 'RAM: ' . $ram_html . '<br>'
                . $storage_html
                . 'OS: ' . $description_array['windows_version'] . '<br>'
                . $gpu_html
                . 'Device Name: ' . $description_array['device_name'] . '<br>'
                . ($item->item_type_id == 8 ? 'Serial Number: ' . $description_array['serial_number'] . '<br>' : '')
                . '</div>';
            }

            $data = [
                'status'=>$query->status,
                'issued_at'=>Carbon::parse($query->issued_at)->format('m-d-Y'),
                'removed_at'=>$query->removed_at?Carbon::parse($query->removed_at)->format('m-d-Y'):'',
                'remarks'=>$query->remarks,
                'name'=>$query->item_inventory->name ?? 'No item name',
                'description'=>$description,
                'serial_number'=>$query->item_inventory->serial_number,
            ];

            $payload = base64_encode(json_encode($data));
            return response()->json(['status' => 'success', 'message'=>'success','payload'=>$payload]);
       }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function info_issued_to(Request $rq)
    {
        try {

            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsAccountabilityIssuedTo::with('employee')->find($id);

            $employee = $query->employee;
            $emp_details = $employee->emp_details;

            $data = [
                'status'=>$query->status,
                'issued_at'=>$query->issued_at,
                'removed_at'=>$query->removed_at,
                'remarks'=>$query->remarks,
                'name'=>$query->employee->fullname(),
                'department'=>$emp_details->department->name,
                'position'=>$emp_details->position->name,
                'emp_no'=>$employee->emp_no,
            ];

            $payload = base64_encode(json_encode($data));
            return response()->json(['status' => 'success', 'message'=>'success','payload'=>$payload]);
       }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function add_accountability_item(Request $rq)
    {
        try {
            DB::beginTransaction();

            $accountability_id = Crypt::decrypt($rq->accountability_id);
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            ImsAccountabilityItem::insert([
                'accountability_id' =>$accountability_id,
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

    public function add_personnel(Request $rq)
    {
        try {
            DB::beginTransaction();

            $accountability_id = Crypt::decrypt($rq->accountability_id);
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = Employee::find($id);
            $emp_details = $query->emp_details;

            ImsAccountabilityIssuedTo::insert([
                'accountability_id' => $accountability_id,
                'emp_id'=>$id,
                'department_id'=>$emp_details->department_id,
                'position_id'=>$emp_details->position_id,
                'status'=>1,
                'remarks'=>$rq->remarks,
                'issued_at'=>Carbon::now(),
                'created_by'=>$user_id
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

    public function update_accountability(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsAccountability::find($id);
            $query->form_no = $rq->form_no;
            $query->issued_at = Carbon::createFromFormat('m-d-Y', $rq->date_issued)->format('Y-m-d');
            $query->returned_at = isset($rq->returned_at) ?Carbon::createFromFormat('m-d-Y', $rq->returned_at)->format('Y-m-d') : null;

            $query->issued_by = Crypt::decrypt($rq->issued_by);
            $query->received_by = Crypt::decrypt($rq->received_by);
            $query->updated_by = $user_id;
            $query->status = $rq->accountability_status;
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

    public function update_issued_items(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsAccountabilityItem::find($id);

            $query->status = $rq->status;
            $query->issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
            $query->removed_at = $rq->removed_at ? Carbon::createFromFormat('m-d-Y', $rq->removed_at)->format('Y-m-d') : null;
            $query->remarks = $rq->remarks;
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

    public function update_issued_to(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsAccountabilityIssuedTo::find($id);
            $query->issued_at = Carbon::createFromFormat('m-d-Y', $rq->issued_at)->format('Y-m-d');
            $query->removed_at = $rq->removed_at ? Carbon::createFromFormat('m-d-Y', $rq->removed_at)->format('Y-m-d') : null;
            $query->remarks = $rq->remarks;
            $query->status = $rq->status;
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

    public function remove_issued_item(Request $rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsAccountabilityItem::with('item_inventory')->find($id);

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

    public function remove_issued_to(Request $rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->encrypted_id);
            $user_id = Auth::user()->emp_id;

            $query = ImsAccountabilityIssuedTo::find($id);
            $query->remarks = $rq->remarks;
            $query->removed_at = Carbon::now();
            $query->status = $rq->status;
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

    // public function info_issued_items(Request $rq)
    // {
    //     $modal = match($rq->modal_type){
    //         "1" => $this->modal_system_unit($rq),
    //         default => $this->modal_other_item($rq)
    //     };
    //     $payload = base64_encode($modal);
    //     return response()->json([
    //         'status'=>'success',
    //         'message' => 'success',
    //         'payload'=>$payload,
    //     ]);
    // }

    // public function modal_system_unit($rq)
    // {
    //     $id = Crypt::decrypt($rq->id);

    //     $query = ImsAccountabilityItem::with('item_inventory')->find($id);
    //     $query->item_inventory->description = json_decode($query->item_inventory->description,true);

    //     $array = $query->item_inventory->description;

    //     $ram = json_decode($array['ram'],true);
    //     foreach($ram as $row){
    //         $rq = $rq->merge([
    //             'id' => null,
    //             'view'=>'1',
    //             'type'=>'search_ram',
    //             'search'=>$row['name']
    //         ]);
    //         $ram_options[] =[
    //             'html' =>(new ItemOption)->list($rq),
    //             'serial_number'=>$row['serial_number'],
    //         ];
    //     };

    //     $storage = json_decode($array['storage'],true);
    //     foreach($storage as $row){
    //         $rq = $rq->merge([
    //             'id' => null,
    //             'view'=>'1',
    //             'type'=>'search_storage',
    //             'search'=>$row['name']

    //         ]);
    //         $storage_options[] =[
    //             'html' =>(new ItemOption)->list($rq),
    //             'serial_number'=>$row['serial_number'],
    //         ];
    //     };

    //     $gpu = json_decode($array['gpu'],true);
    //     foreach($gpu as $row){
    //         if(strtolower($row['type']) =='integrated'){
    //             continue;
    //         }

    //         $rq = $rq->merge([
    //             'id' => null,
    //             'view'=>'1',
    //             'type'=>'search_gpu',
    //             'search'=>$row['name']

    //         ]);
    //         $gpu_options[] =[
    //             'html' =>(new ItemOption)->list($rq),
    //             'serial_number'=>'',
    //         ];
    //     };

    //     return view('employee.pages.accountability.modal.edit_system_unit', compact('query','ram_options','storage_options','gpu_options'))->render();

    // }

    // public function modal_other_item($rq)
    // {
    //     $id = Crypt::decrypt($rq->id);
    //     $query = ImsAccountabilityItem::with('item_inventory')->find($id);

    //     return view('employee.pages.accountability.modal.edit_other_item', compact('query'))->render();
    // }

    // public function update_issued_items(Request $rq)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $response = match($rq->update_type){
    //             'system unit' => $this->update_system_unit($rq),
    //             'laptop' => $this->update_laptop($rq),
    //             'other_item' => $this->update_other_item($rq),
    //             default => false,
    //         };

    //         DB::commit();
    //         return $response;
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);

    //     }
    // }

    // public function update_system_unit($rq)
    // {
    //     $id = Crypt::decrypt($rq->encrypted_id);
    //     $query = ImsAccountabilityItem::with('item_inventory')->find($id);

    //     $array_storage = [];
    //     $storage = json_decode($rq->storage,true);
    //     foreach($storage as $row)
    //     {
    //         $search_id = Crypt::decrypt($row['id']);
    //         $search = ImsItem::find($search_id);
    //         $array_storage[]=[
    //             'name'=>$search->name,
    //             'description' =>$search->description,
    //             'type' =>$search->item_type->name,
    //             'serial_number'=>$row['serial_number'],
    //         ];
    //     }

    //     $array_ram = [];
    //     $ram = json_decode($rq->ram,true);
    //     foreach($ram as $row)
    //     {
    //         $search_id = Crypt::decrypt($row['id']);
    //         $search = ImsItem::find($search_id);

    //         $array_ram[]=[
    //             'name'=>$search->name,
    //             'description' =>$search->description,
    //             'serial_number'=>$row['serial_number'],
    //         ];
    //     }

    //     $array_gpu = [];
    //     $gpu = json_decode($rq->gpu,true);
    //     foreach($gpu as $row){
    //         $search_id = Crypt::decrypt($row['id']);
    //         $search = ImsItem::find($search_id);
    //         $array_gpu[]=[
    //             'name'=>$search->name,
    //             'description' =>$search->description,
    //             'serial_number'=>$row['serial_number'],
    //             'type' => 'Dedicated',
    //         ];
    //     }

    //     $query->item_inventory->name = $rq->item;
    //     $query->item_inventory->description = json_encode([
    //         'cpu'=>$rq->cpu,
    //         'ram'=>json_encode($array_ram),
    //         'storage'=>json_encode($array_storage),
    //         'gpu'=>json_encode($array_gpu),
    //         'device_name'=>$rq->device_name,
    //         'os_installed_date'=>$rq->os_installed_date,
    //         'windows_version'=>$rq->windows_version,
    //     ]);

    //     $query->remarks = $rq->remarks;
    //     $query->updated_by = Auth::user()->emp_id;

    //     $query->item_inventory->save();
    //     $query->save();

    //     return response()->json(['status' => 'success', 'message'=>'Update Success']);
    // }

    // public function update_laptop($rq)
    // {

    // }

    // public function update_other_item($rq)
    // {
    //     $id = Crypt::decrypt($rq->encrypted_id);
    //     $user_id = Auth::user()->emp_id;
    //     $query = ImsAccountabilityItem::with('item_inventory')->find($id);

    //     $query->item_inventory->name = $rq->item;
    //     $query->item_inventory->description = $rq->description;
    //     $query->item_inventory->updated_by = $user_id;

    //     $query->remarks = $rq->remarks;
    //     $query->updated_by = $user_id;

    //     $query->item_inventory->save();
    //     $query->save();

    //     return response()->json(['status' => 'success', 'message'=>'Update Success']);
    // }

    // public function delete_issued_item(Request $rq)
    // {
    //     $id = Crypt::decrypt($rq->encrypted_id);
    //     $user_id = Auth::user()->emp_id;
    //     $query = ImsAccountabilityItem::with('item_inventory')->find($id);

    //     if($query->status == 1 && $query->item_inventory->status == 2){
    //         $query->status = 2; //Returned
    //         $query->updated_by = $user_id;

    //         $query->item_inventory->status = 1 ;  //Available in inventory
    //         $query->item_inventory->updated_by =$user_id ;  //

    //         $query->save();
    //         $query->item_inventory->save();

    //         return response()->json(['status' => 'success', 'message'=>'Status is updated']);
    //     }

    // }

    // public function delete_issued_to(Request $rq)
    // {
    //     $id = Crypt::decrypt($rq->encrypted_id);
    //     $user_id = Auth::user()->emp_id;
    //     $query = ImsAccountabilityIssuedTo::find($id);

    //     if($query->status == 1 ){

    //         $query->status = 2;
    //         $query->removed_at = Carbon::now();
    //         $query->updated_by = $user_id;

    //         $query->save();

    //         return response()->json(['status' => 'success', 'message'=>'Status is updated']);
    //     }

    // }

}
