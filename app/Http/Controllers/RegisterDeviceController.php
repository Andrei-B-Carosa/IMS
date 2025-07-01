<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImsAccountability;
use App\Models\ImsAccountabilityIssuedTo;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItem;
use App\Models\ImsItemBrand;
use App\Models\ImsItemInventory;
use App\Models\ImsItemInventoryLog;
use App\Models\ImsItemType;
use App\Service\Select\ItemOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class RegisterDeviceController extends Controller
{
    public function fetch()
    {
        return view('guest.fetch_device');
    }

    public function register(Request $rq)
    {
        try {
            // Get the encrypted data
            $encodedData = $rq->query('data');

            // Base64 decode
            $json = base64_decode($encodedData);

            // Convert JSON to array
            $data = json_decode($json, true);

            $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accessories']);
            $other_accessories = (new ItemOption)->list($rq);

            return view('guest.register_device',compact('data','other_accessories'));

        } catch (\Exception $e) {
            return "Invalid or corrupted data: " . $e->getMessage();
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();

            $encodedData = $rq->dataParam;
            $json = base64_decode($encodedData);
            $data = json_decode($json, true);
            $device_type = strtolower($data['device_type']);

            $register_device = match($device_type){
                'system unit' =>$this->register_desktop($rq,$data),
                'laptop' =>$this->register_laptop($rq,$data),
                default => false
            };
            if($register_device === false){
                return response()->json(['status' => 'error', 'message'=>'Something went wrong on the registration of device. Try again later']);
            }

            $register_accessories = $this->register_accessories($rq,$data,$device_type);
            if($register_accessories === false){
                return response()->json(['status' => 'error', 'message'=>'Something went wrong on the registration of accessories. Try again later']);
            }

            $create_accountability = $this->accountability($rq,$register_device,$register_accessories);
            if($create_accountability === false){
                return response()->json(['status' => 'error', 'message'=>'Something went wrong on creating your accountability form. Try again later']);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'Registration Success']);
        }catch(Exception $e){

            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function register_desktop($rq,$data)
    {
        $array_storage = [];
        foreach($data['storage'] as $storage)
        {
            $array_storage[]=[
                // 'model'=>$storage['model'],
                // 'size' =>$storage['size_gb'].'GB',
                // 'type' =>$storage['type'],
                'name'=>$storage['model'].' '.$storage['type'],
                'description'=>$storage['size_gb'].'GB '.$storage['type'],
                'serial_number'=>$storage['serial_number'],
            ];
            ImsItem::registerItem([
                'name'=>$storage['model'].' '.$storage['type'],
                'description'=>$storage['size_gb'].'GB '.$storage['type'],
                'brand'=>false,
            ],$storage['type']);
        }

        $array_ram = [];
        foreach($data['ram']['sticks'] as $ram)
        {
            $array_ram[]=[
                'name'=>$ram['manufacturer'].' '.$ram['size_gb'].'GB RAM',
                'description'=>$ram['manufacturer'].' RAM',
                'serial_number'=>$ram['serial_number'],
            ];

            ImsItem::registerItem([
                'name'=>$ram['manufacturer'].' '.$ram['size_gb'].'GB RAM',
                'description'=>$ram['manufacturer'].' RAM',
                'brand'=>$ram['manufacturer'],
            ],'RAM');
        }

        $array_gpu = [];
        foreach($data['gpu'] as $gpu)
        {
            $array_gpu[]=[
                'name'=>$gpu['name'],
                'description'=>$gpu['name'],
                'type' =>$gpu['type'],
                'serial_number' =>null,
            ];
            if(strtolower($gpu['type']) != 'integrated'){
                ImsItem::registerItem([
                    'name'=>$gpu['name'],
                    'description'=>$gpu['name'],
                    'brand'=>$gpu['vendor']
                ],'GPU');
            }
        }

        $name = $data['device_type'].' '.$data['cpu'];
        $description = json_encode([
            'cpu'=>$data['cpu'],
            'ram'=>json_encode($array_ram),
            'storage'=>json_encode($array_storage),
            'gpu'=>json_encode($array_gpu),
            'device_name'=>$data['device_name'],
            'os_installed_date'=>$data['os_installed_date'],
            'windows_version'=>$data['windows_version'],
        ]);

        $desktop_id = ImsItemType::getDesktopId();
        if(!$desktop_id){ return false; }

        return ImsItemInventory::create([
            'item_type_id' => $desktop_id,
            'name' =>$name,
            'description'=>$description,
            'status' =>2,
            'company_location_id' =>Crypt::decrypt($rq->company_location),
            'remarks' => 'Data came from online registration',
            'created_by'=>1
        ]);
    }

    public function register_laptop($rq,$data)
    {
        $created_by = Crypt::decrypt($rq->issued_by);

        $array_storage = [];
        foreach($data['storage'] as $storage)
        {
            $array_storage[]=[
                'name'=>$storage['model'].' '.$storage['type'],
                'description'=>$storage['size_gb'].'GB '.$storage['type'],
                'serial_number'=>$storage['serial_number'],
            ];
            ImsItem::registerItem([
                'name'=>$storage['model'].' '.$storage['type'],
                'description'=>$storage['size_gb'].'GB '.$storage['type'],
                'brand'=>false,
            ],$storage['type']);
        }

        $array_ram = [];
        foreach($data['ram']['sticks'] as $ram)
        {
            $array_ram[]=[
                'name'=>$ram['manufacturer'].' '.$ram['size_gb'].'GB RAM',
                'description'=>$ram['manufacturer'].' RAM',
                'serial_number'=>$ram['serial_number'],
            ];

            ImsItem::registerItem([
                'name'=>$ram['manufacturer'].' '.$ram['size_gb'].'GB RAM',
                'description'=>$ram['manufacturer'].' RAM',
                'brand'=>$ram['manufacturer'],
            ],'RAM');
        }

        $array_gpu = [];
        foreach($data['gpu'] as $gpu)
        {
            $array_gpu[]=[
                'name'=>$gpu['name'],
                'description'=>$gpu['name'],
                'type' =>$gpu['type']
            ];
            if(strtolower($gpu['type']) != 'integrated'){
                ImsItem::registerItem([
                    'name'=>$gpu['name'],
                    'description'=>$gpu['name'],
                    'brand'=>$gpu['vendor']
                ],'GPU');
            }
        }

        $name = $data['model'];
        $description = json_encode([
            'model'=>$data['model'],
            'brand'=>$data['brand'],
            'serial_number'=>$data['serial_number'],
            'cpu'=>$data['cpu'],
            'ram'=>json_encode($array_ram),
            'storage'=>json_encode($array_storage),
            'gpu'=>json_encode($array_gpu),
            'device_name'=>$data['device_name'],
            'os_installed_date'=>$data['os_installed_date'],
            'windows_version'=>$data['windows_version'],
        ]);

        $brand_id = ImsItemBrand::getBrandId($data['brand']);
        $laptop_id = ImsItemType::getLaptopId();
        if(!$laptop_id){   return false; }

        return ImsItemInventory::create([
            'item_type_id' => $laptop_id,
            'item_brand_id' => $brand_id,
            'name' =>$name,
            'description'=>$description,
            'serial_number'=>$data['serial_number'],
            'status' =>2,
            'remarks' => 'Data came from online registration',
            'company_location_id' =>Crypt::decrypt($rq->company_location),
            'created_by'=>$created_by,
            'supplier_id'=>1,
            'received_at'=>Carbon::now(),
        ]);
    }

    public function register_accessories($rq,$dataParam,$device_type)
    {
        $accessory_ids =[];
        $data = json_decode($rq->other_accessories,true);
        if(!empty($data)){
            foreach($data as $row)
            {
                $accesories_id = Crypt::decrypt($row['id']);
                $query = ImsItem::find($accesories_id);

                if(!$query){
                    return false;
                }

                $accessory_ids[] = ImsItemInventory::create([
                    'item_brand_id' =>$query->item_brand_id,
                    'item_type_id' => $query->item_type_id,
                    'name'=>$query->name,
                    'description'=>$query->description,
                    'serial_number'=>$row['serial_number'],
                    'status'=>2,
                    'remarks'=>'Data came from online registration',
                    'company_location_id' =>Crypt::decrypt($rq->company_location),
                    'created_by'=>1,
                    'supplier_id'=>1,
                    'received_at'=>Carbon::now(),

                ]);
            }
        }
        if($device_type == 'system unit' && !empty($dataParam['monitors'])){
            foreach($dataParam['monitors'] as $row){
                $monitor = ImsItem::whereRaw('LOWER(name) = ?', [strtolower($row['name'])])->where('is_active', 1)->first();
                if(!$monitor){
                    $monitor = ImsItem::registerItem([
                        'name'=>$row['name'],
                        'description'=>$row['manufacturer'].' Monitor',
                        'brand'=>$row['manufacturer'],
                    ],'Monitor');
                }
                $accessory_ids[] = ImsItemInventory::create([
                    'item_brand_id' =>$monitor->item_brand_id,
                    'item_type_id' => $monitor->item_type_id,
                    'name'=>$monitor->name,
                    'description'=>$monitor->description,
                    'serial_number'=>$row['serial_number'],
                    'status'=>2,
                    'remarks'=>'Data came from online registration',
                    'company_location_id' =>Crypt::decrypt($rq->company_location),
                    'created_by'=>1,
                    'supplier_id'=>1,
                    'received_at'=>Carbon::now(),
                ]);
            }
        }
        return $accessory_ids;
    }

    public function accountability($rq,$device,$accessories)
    {
        $issued_to = json_decode($rq->issued_to,true);
        $issued_by = Crypt::decrypt($rq->issued_by);

        if(empty($issued_to) || !$issued_by){
            return false;
        }

        $received_by = Crypt::decrypt($issued_to[0]['employee']);
        $accountability = ImsAccountability::create([
            'issued_by'=> $issued_by,
            'issued_at'=>Carbon::now(),
            'received_by' => $received_by,
            'form_no' => $rq->form_no,
            'remarks' => 'Data came from online registration',
            'created_by'=>$issued_by,
        ]);

        //Insert accountabled employee
        $employee = [];
        foreach($issued_to as $row)
        {
            $emp_id = Crypt::decrypt($row['employee']);
            $find_emp_id = Employee::find($emp_id);
            if(!$find_emp_id)
            {
                return false;
            }

            $employee[] = $find_emp_id->fullname();
            ImsAccountabilityIssuedTo::create([
                'accountability_id' => $accountability->id,
                'emp_id'=>$emp_id,
                'issued_at'=>Carbon::now(),
                'department_id'=>$find_emp_id->emp_details->department_id,
                'position_id'=>$find_emp_id->emp_details->position_id,
                'remarks'=>'This data is from online registration',
                'created_by'=>$issued_by,
            ]);
        }

        //This array is for laptop/system unit
        $items_array[] = [
            'item_inventory_id' =>$device->id,
            'accountability_id' =>$accountability->id,
            'status'=>1,
            'issued_at'=>Carbon::now(),
            'remarks'=>'This data is from online registration',
            'created_by'=>$issued_by,
        ];

        //This array is accessories
        foreach($accessories as $item)
        {
            $items_array[]=[
                'item_inventory_id' =>$item->id,
                'accountability_id' =>$accountability->id,
                'status'=>1,
                'issued_at'=>Carbon::now(),
                'remarks'=>'This data is from online registration',
                'created_by'=>$issued_by,
            ];

            ImsItemInventoryLog::create([
                'item_inventory_id'=>$item->id,
                'emp_id'=>1,
                'activity_type'=>2,
                'activity_table'=>'ACCOUNTABILITY',
                'activity_log'=>'Item is currently issued to '. implode(', ', $employee).'. The accountability form no is: "'. $rq->form_no.'"',
                'created_by'=>1
            ]);

        }

        //This log is for laptop/system unit
        ImsItemInventoryLog::create([
            'item_inventory_id'=>$device->id,
            'emp_id'=>1,
            'activity_type'=>2,
            'issued_at'=>Carbon::now(),
            'activity_table'=>'ACCOUNTABILITY',
            'activity_log'=>'Item is currently issued to '. implode(', ', $employee).'. The accountability form no is: "'. $rq->form_no.'"',
            'created_by'=>$issued_by
        ]);
        return ImsAccountabilityItem::insert($items_array);
    }
}
