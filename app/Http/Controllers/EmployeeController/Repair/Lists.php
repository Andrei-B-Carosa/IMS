<?php

namespace App\Http\Controllers\EmployeeController\Repair;

use App\Http\Controllers\Controller;
use App\Models\ImsItemInventory;
use App\Models\ImsItemRepairLog;
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
        $filter_location = $rq->filter_location &&  $rq->filter_location != 'all' ? Crypt::decrypt($rq->filter_location) : false;
        $filter_category = $rq->filter_category &&  $rq->filter_category != 'all' ? Crypt::decrypt($rq->filter_category) : false;

        $data = ImsStoredProcedure::sp_get_repair_logs(
            $filter_status,
            $filter_location,
            $filter_category
        );

        $data->transform(function ($item, $key) {
            $item_type = $item->item_type_id;
            $description = $item->description;

            if($item_type == 1 || $item_type == 8) {
                $array = json_decode($description,true);
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
                $description = '<div class="fs-6">'
                . ($item->item_type_id == 8 ? 'Model: ' . $array['model'] . '<br>' : '')
                . 'CPU: ' . $array['cpu'] . '<br>'
                . 'RAM: ' . $ram_html . '<br>'
                . $storage_html
                . 'OS: ' . $array['windows_version'] . '<br>'
                . $gpu_html
                . 'Device Name: ' . $array['device_name'] . '<br>'
                . '</div>';
            }

            $item->count = $key + 1;

            $item->description = $description;
            $item->start_at = Carbon::parse($item->start_at)->format('m-d-Y');
            $item->end_at = isset($item->end_at) ? Carbon::parse($item->end_at)->format('m-d-Y'):'--';
            $item->location =  $item->company_location;

            $item->encrypted_id = Crypt::encrypt($item->id);
            $item->item_inventory_id = Crypt::encrypt($item->item_inventory_id);
            $item->accountability_id = Crypt::encrypt($item->accountability_id);

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

    public function item_details(Request $rq)
    {
        try {

            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsItemInventory::find($id);

            $array = [];
            $accountable_to = [];
            if($query){
                $active_accountability = $query->active_accountability_item;
                if($active_accountability){
                    foreach($active_accountability->accountable_to as $row){
                        $accountable_to[] = $row->employee->fullname();
                    }
                }
                $array= [
                    'name'=>$query->name,
                    'description'=>$query->description_construct(),
                    'status'=>$query->status,
                    'serial_number'=>$query->serial_number,
                    'form_no'=>optional(optional($active_accountability)->accountability)->form_no,
                    'accountable_to'=> implode(', ', $accountable_to),
                    'status_badge'=>$query->status_badge(),
                ];
            }

            $payload = base64_encode(json_encode($array));
            return response()->json(['status' => 'success', 'message'=>'Success','payload'=>$payload]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }

    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = ImsItemRepairLog::find($id);

            $inventory = $query->item_inventory;
            $active_accountability = $inventory->active_accountability_item;
            $accountable_to = [];
            if($active_accountability){
                foreach($active_accountability->accountable_to as $row){
                    $accountable_to[] = $row->employee->fullname();
                }
            }

            $payload = [
                'initial_diagnosis' =>$query->initial_diagnosis,
                'tag_number'=>strtoupper($query->item_inventory->name) .' ( '.$query->item_inventory->tag_number.' )',
                'work_to_be_done' =>$query->work_to_be_done,
                'repair_type' =>$query->repair_type,
                'start_at' =>Carbon::parse($query->start_at)->format('m-d-Y'),
                'end_at' => $query->end_at?Carbon::parse($query->end_at)->format('m-d-Y'):null,
                'status' =>$query->status,

                'name'=>$inventory->name,
                'description'=>$inventory->description_construct(),
                'serial_number'=>$inventory->serial_number,
                'form_no'=>optional(optional($active_accountability)->accountability)->form_no,
                'accountable_to'=> implode(', ', $accountable_to),
                'encrypted_id' =>Crypt::encrypt($query->id),
            ];
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = isset($rq->id)?Crypt::decrypt($rq->id):false;
            $item_inventory_id = isset($rq->device) ? Crypt::decrypt($rq->device): false;
            if($id==false && $item_inventory_id==false){
                response()->json(['status' => 'error', 'message'=>'Select a device first']);
            }
            $attribute = ['id' =>$id];
            $value = [
                'issued_by' => Auth::user()->emp_id,
                'repair_type' => $rq->repair_type,
                'start_at' =>Carbon::createFromFormat('m-d-Y',$rq->start_at)->format('Y-m-d'),
                'end_at' => isset($rq->end_at)? Carbon::createFromFormat('m-d-Y',$rq->end_at)->format('Y-m-d'):null,
                'initial_diagnosis' =>$rq->initial_diagnosis,
                'work_to_be_done' =>isset($rq->work_to_be_done)? $rq->work_to_be_done:null,
                'status' =>$rq->status,
            ];
            if($item_inventory_id){
                $query = ImsItemInventory::find($item_inventory_id);
                $value['item_inventory_id'] = $query->id;
                $value['item_inventory_status'] = $query->status;
                if($query->active_accountability_item && !$id){
                    $active_accountability = $query->active_accountability_item;
                    $accountableToList  = [];
                    foreach($active_accountability->accountable_to as $accountable_to){
                        $accountableToList[] = $accountable_to->employee->fullname();
                    }
                    $value['is_issued'] = 1;
                    $value['accountability_id'] = $active_accountability->accountability->id;
                    $value['accountability_form_no'] = $active_accountability->accountability->form_no;
                    $value['last_accountable_to'] =implode(', ', $accountableToList);
                }
            }
            if($id){
                $value['updated_by'] = Auth::user()->emp_id;
            }else{
                $value['created_by'] = Auth::user()->emp_id;
            }
            ImsItemRepairLog::updateOrCreate($attribute,$value);
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Success']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function delete(Request $rq)
    {
        try {
            DB::beginTransaction();
            $id = Crypt::decrypt($rq->encrypted_id);
            $query = ImsItemRepairLog::find($id);

            $query->is_deleted = 1;
            $query->remarks = $rq->remarks;
            $query->deleted_by = Auth::user()->emp_id;
            $query->updated_by = Auth::user()->emp_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Success']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

}
