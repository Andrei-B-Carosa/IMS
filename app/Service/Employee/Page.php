<?php

namespace App\Service\Employee;

use App\Models\Employee;
use App\Models\ImsAccountability;
use App\Models\ImsItem;
use App\Models\ImsItemInventory;
use App\Models\ImsMaterialIssuance;
use App\Service\Select\CompanyLocationOptions;
use App\Service\Select\EmployeeOptions;
use App\Service\Select\InventoryOption;
use App\Service\Select\ItemBrandOption;
use App\Service\Select\ItemOption;
use App\Service\Select\ItemTypeOption;
use App\Service\Select\SupplierOptions;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Page
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function accountability_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = ImsAccountability::with('issued_by_emp')->withCount([
                'accountability_item as accountability_item_status_1_count' => function ($query) {
                    $query->where('status', 1);
                },
                'accountability_item as accountability_item_status_2_count' => function ($query) {
                    $query->where('status', 2);
                },
                'issued_to as issued_to_status_1_count' => function ($query) {
                    $query->where('status', 1);
                },
            ])->find($id);

            $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accessories']);
            $other_accessories = (new ItemOption)->list($rq);

            return view('employee.pages.accountability.accountability_details', compact('data','other_accessories'))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function new_accountability($rq)
    {

        // $issued_by_option = '<option value="'.Crypt::encrypt(Auth::user()->emp_id).'">'.Auth::user()->employee->fullname().'</option>';

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accountability_items']);
        $inventory_options = (new InventoryOption)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'employee']);
        $employee_option = (new EmployeeOptions)->list($rq);

        $emp_id = Crypt::encrypt(Auth::user()->emp_id);
        $rq = $rq->merge(['id' => $emp_id, 'view'=>'1', 'type'=>'mis_personnel']);
        $issued_by_option = (new EmployeeOptions)->list($rq);

        return view('employee.pages.accountability.new_accountability',compact('inventory_options','issued_by_option','employee_option'))->render();
    }

    public function inventory_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);

            $data = ImsItemInventory::with(['item_type','updated_by_emp','received_by_emp'])->find($id);

            $array_specs = [];
            if($data->item_type_id == 1 || $data->item_type_id == 8){
                $data->description = json_decode($data->description,true);
                $array = $data->description;

                $ram = json_decode($array['ram'],true);
                if(count($ram) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_ram',
                        'search'=>null
                    ]);
                    $ram_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                        'serial_number'=>null,
                    ];
                }else{
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
                }


                $storage = json_decode($array['storage'],true);
                if(count($storage) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_storage',
                        'search'=>null
                    ]);
                    $storage_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                        'serial_number'=>null,

                    ];
                }else{
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
                }

                $gpu = json_decode($array['gpu'],true);
                if(count($gpu) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_gpu',
                        'search'=>null
                    ]);
                    $gpu_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                        'serial_number'=>null,
                    ];
                }else{
                    foreach($gpu as $row){
                        if(strtolower($row['type']) =='integrated'){
                            $row['name'] = null;
                            $row['serial_number'] = null;
                        }

                        $rq = $rq->merge([
                            'id' => null,
                            'view'=>'1',
                            'type'=>'search_gpu',
                            'search'=>$row['name']

                        ]);
                        $gpu_options[] =[
                            'html' =>(new ItemOption)->list($rq),
                            'serial_number'=>isset($row['serial_number'])? $row['serial_number'] :'',
                        ];
                    };
                }

                $array_specs = [
                    'ram_options'=>$ram_options,
                    'storage_options'=>$storage_options,
                    'gpu_options'=>$gpu_options,
                ];
            }

            $rq = $rq->merge(['id' => Crypt::encrypt($data->item_type_id), 'view'=>'1', 'type'=>'get_item_type']);
            $item_type_options = (new ItemTypeOption)->list($rq);

            $rq = $rq->merge(['id' => Crypt::encrypt($data->item_brand_id), 'view'=>'1', 'type'=>'get_item_brand']);
            $item_brand_options = (new ItemBrandOption)->list($rq);

            $rq = $rq->merge(['id' => Crypt::encrypt($data->received_by), 'view'=>'1', 'type'=>'mis_personnel']);
            $mis_personnel_options = (new EmployeeOptions)->list($rq);

            $rq = $rq->merge(['id' => Crypt::encrypt($data->supplier_id), 'view'=>'1', 'type'=>'get_supplier']);
            $supplier_options = (new SupplierOptions)->list($rq);

            $rq = $rq->merge(['id' => Crypt::encrypt($data->company_location_id), 'view'=>'1', 'type'=>'options']);
            $clocation_options = (new CompanyLocationOptions)->list($rq);

            $status = ['0'=>'Disposed','1' => 'Available', '2' => 'Issued'];
            if($data->status==4){ $status['4']='Under Repair'; }

            return view('employee.pages.inventory.inventory_details', compact(
                'data','array_specs','item_type_options','item_brand_options','mis_personnel_options','supplier_options',
                'clocation_options','status'
                ))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function new_inventory($rq)
    {
        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'mis_personnel']);
        $mis_personnel_options = (new EmployeeOptions)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_supplier']);
        $supplier_options = (new SupplierOptions)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_item']);
        $item_options = (new ItemOption)->list($rq);

        return view('employee.pages.inventory.new_inventory',compact('mis_personnel_options','supplier_options','item_options'))->render();
    }

    public function new_consumables($rq)
    {
        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'mis_personnel']);
        $mis_personnel_options = (new EmployeeOptions)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_supplier']);
        $supplier_options = (new SupplierOptions)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_consumable']);
        $item_options = (new ItemOption)->list($rq);

        return view('employee.pages.inventory.new_consumables',compact('mis_personnel_options','supplier_options','item_options'))->render();
    }

    public function new_material_issuance($rq)
    {

        $issued_by_option = '<option value="'.Crypt::encrypt(Auth::user()->emp_id).'">'.Auth::user()->employee->fullname().'</option>';

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'material_issuance_items']);
        $inventory_options = (new InventoryOption)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'employee']);
        $employee_option = (new EmployeeOptions)->list($rq);

        return view('employee.pages.material_issuance.new_material_issuance',compact('inventory_options','issued_by_option','employee_option'))->render();
    }

    public function material_issuance_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = ImsMaterialIssuance::with('issued_by_emp')->find($id);

            $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accessories']);
            $other_accessories = (new ItemOption)->list($rq);

            return view('employee.pages.material_issuance.material_issuance_details', compact('data','other_accessories'))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function item_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);

            $data = ImsItem::with(['item_type','updated_by_emp'])->find($id);

            $array_specs = [];
            if($data->item_type_id == 1 || $data->item_type_id == 8){
                $data->description = json_decode($data->description,true);
                $array = $data->description;

                $ram_options = [];
                $ram = json_decode($array['ram'],true);
                if(count($ram) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_ram',
                        'search'=>null
                    ]);
                    $ram_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                    ];
                }else{
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
                }


                $storage_options = [];
                $storage = json_decode($array['storage'],true);
                if(count($storage) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_storage',
                        'search'=>null
                    ]);
                    $storage_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                    ];
                }else{
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
                }


                $gpu_options = [];
                $gpu = json_decode($array['gpu'],true);
                if(count($gpu) == 0){
                    $rq = $rq->merge([
                        'id' => null,
                        'view'=>'1',
                        'type'=>'search_gpu',
                        'search'=>null
                    ]);
                    $gpu_options[] =[
                        'html' =>(new ItemOption)->list($rq),
                    ];
                }else{
                    foreach($gpu as $row){
                        if(strtolower($row['type']) =='integrated'){
                            $row['name'] = null;
                            $row['serial_number'] = null;
                        }
                        $rq = $rq->merge([
                            'id' => null,
                            'view'=>'1',
                            'type'=>'search_gpu',
                            'search'=>$row['name']

                        ]);
                        $gpu_options[] =[
                            'html' =>(new ItemOption)->list($rq),
                            'serial_number'=>$row['serial_number'] ?? '',
                        ];
                    };
                }

                $array_specs = [
                    'ram_options'=>$ram_options,
                    'storage_options'=>$storage_options,
                    'gpu_options'=>$gpu_options,
                ];
            }


            $rq = $rq->merge(['id' => Crypt::encrypt($data->item_type_id), 'view'=>'1', 'type'=>'get_item_type']);
            $item_type_options = (new ItemTypeOption)->list($rq);

            $rq = $rq->merge(['id' => Crypt::encrypt($data->item_brand_id), 'view'=>'1', 'type'=>'get_item_brand']);
            $item_brand_options = (new ItemBrandOption)->list($rq);


            $rq = $rq->merge(['id' => Crypt::encrypt($data->received_by), 'view'=>'1', 'type'=>'mis_personnel']);
            $mis_personnel_options = (new EmployeeOptions)->list($rq);


            return view('employee.pages.settings.file_maintenance.item_details', compact('data','array_specs','item_type_options','item_brand_options','mis_personnel_options'))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function employee_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = Employee::find($id);
            $isRegisterEmployee = false;

            $emp_details = $query->emp_details;
            $tenure = Carbon::parse($emp_details->date_employed)->diffInYears(Carbon::now());
            $data = [
                'fullname'=>$query->fullname(),
                'department'=> $emp_details->department->name,
                'dept_code'=> $emp_details->department->code,
                'position'=> $emp_details->position->name,
                'date_employed'=> isset($emp_details->date_employed)?Carbon::parse($emp_details->date_employed)->format('m/d/Y'):'--',
                'tenure'=> $tenure > 0 ? $tenure : '--',
                'employment_type' =>$emp_details->employment->name,
                'c_email'=> $query->emp_account? $query->emp_account->c_email:false,
                'work_status' => $emp_details->work_status,
                'is_active' => $emp_details->is_active,
            ];
            return view('employee.pages.settings.employee_list.employee_details.employee_details', ['data'=>$data,'isRegisterEmployee'=>$isRegisterEmployee])->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function new_item($rq)
    {
        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_item_type']);
        $item_type_options = (new ItemTypeOption)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'get_item_brand']);
        $item_brand_options = (new ItemBrandOption)->list($rq);

        $rq = $rq->merge(['id' => null,'view'=>'1','type'=>'search_ram', 'search'=>null]);
        $ram_options =(new ItemOption)->list($rq);

        $rq = $rq->merge(['id' => null,'view'=>'1','type'=>'search_storage', 'search'=>null]);
        $storage_options =(new ItemOption)->list($rq);

        $rq = $rq->merge(['id' => null,'view'=>'1','type'=>'search_gpu', 'search'=>null]);
        $gpu_options =(new ItemOption)->list($rq);

        return view('employee.pages.settings.file_maintenance.new_item',compact('item_type_options','item_brand_options','ram_options','storage_options','gpu_options'))->render();
    }

}
