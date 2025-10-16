<?php

namespace App\Http\Controllers\EmployeeController\Settings\FileMaintenance;

use App\Http\Controllers\Controller;
use App\Models\ImsItem;
use App\Models\ImsItemBrand;
use App\Models\ImsItemType;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Item extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = ImsItem::when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where('is_deleted',null)
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

                $description = '<div class="fs-6">'
                . ($item->item_type_id == 8 ? 'Model: ' . $array['model'] . '<br>' : '')
                . 'CPU: ' . $array['cpu'] . '<br>'
                . 'RAM: ' . $ram_html . '<br>'
                . $storage_html
                . 'OS: ' . $array['windows_version'] . '<br>'
                . $gpu_html
                . 'Device Name: ' . $array['device_name'] . '<br>'
                // . ($item->item_type_id == 8 ? 'Serial Number: ' . $array['serial_number'] . '<br>' : '')
                . '</div>';
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_update_at = $last_update_at;

            $item->issued_at = $issued_at;
            $item->removed_at = $removed_at;

            $item->name =  $name;
            $item->description = $description;
            $item->price = $item->price;
            $item->item_type = $item->item_type->name ?? '--';
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
            $query = ImsItem::find($id);

            $query->name = $rq->name;

            $item_brand_id = $rq->item_brand? Crypt::decrypt($rq->item_brand):null;
            $item_type_id = $rq->item_type? Crypt::decrypt($rq->item_type):null;

            // if(isset($rq->description) && ($query->item_type_id != 1 && $query->item_type_id !=8)){
            //     $query->description = $rq->description;
            // }

            if($query->item_type_id == 1 || $query->item_type_id == 8){
                $description = json_decode($query->description,true);
                if($query->item_brand_id != $item_brand_id){
                    $description['brand'] = ImsItemBrand::find($item_brand_id)->value('name');
                }
                $query->description = json_encode($description);
            }

            $query->price = $rq->price;
            $query->item_brand_id = $item_brand_id;
            $query->item_type_id = $item_type_id;
            $query->is_active = $rq->is_active;
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
            $query = ImsItem::find($id);

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
                    'serial_number'=>null,
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
                    'serial_number'=>null,
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
                    'serial_number'=>null,
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
                // $description['serial_number']=null;
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

    public function check_item_type(Request $rq)
    {
        $payload = false;
        $name = false;

        if(isset($rq->id)){
            $id = Crypt::decrypt($rq->id);
            $query = ImsItemType::find($id);

            $name = strtolower(trim(preg_replace('/\s+/', ' ', $query->name)));
            $payload = ($name == 'system unit' || $name == 'laptop') ?? false;
        }
        $payload = base64_encode(json_encode([
            'type'=>$name,
            'name' => $name? ucwords(strtolower($name)) : 'Item Details',
            'is_computing_device' => $payload,
        ]));

        return response()->json(['status' => 'success', 'message'=>'success', 'payload'=>$payload]);
    }

    public function new_item(Request $rq)
    {
        try {
            DB::beginTransaction();
            $created_by = Auth::user()->emp_id;

            $item_brand_id = $rq->item_brand? Crypt::decrypt($rq->item_brand):null;
            $item_type_id = $rq->item_type? Crypt::decrypt($rq->item_type):null;
            $description = $rq->description;

            if($item_type_id == 1 || $item_type_id ==8){
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
                        'serial_number'=>null,
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
                        'serial_number'=>null,
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
                        'serial_number'=>null,
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

                if($item_type_id == 8){
                    $description['model']=$rq->laptop_model;
                    $description['brand']=ImsItemBrand::find($item_brand_id)->value('name');
                    $description['serial_number']=null;
                }

                $description = json_encode($description);
            }

            ImsItem::create([
                'item_brand_id' => $item_brand_id,
                'item_type_id' => $item_type_id,
                'name' => $rq->name,
                'description' => $description,
                // 'is_active' => $rq->is_active,
                'price' => $rq->price,
                // 'remarks' => $rq->remarks,
                'created_by' => $created_by,
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

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->encrypted_id);

            $query = ImsItem::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->remarks = $query->remarks.', REASON FOR DELETING: '.$rq->remarks;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => ImsItem::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate(Request $rq)
    {
        try {
            $valid = true;
            $message = '';
            $normalizedName = strtolower(str_replace(' ', '', $rq->name));
            $availableItems = ImsItem::whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalizedName])
            ->where('is_active', 1)
            ->count();
            if ($availableItems > 0) {
                $valid = false;
                $message = '';
            }
            return response()->json(['valid' => $valid, 'message' => $message]);

        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

}
