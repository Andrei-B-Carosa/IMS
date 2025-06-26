<?php

namespace App\Http\Controllers\EmployeeController\Inventory;

use App\Http\Controllers\Controller;
use App\Models\HrisCompanyLocation;
use App\Models\ImsItem;
use App\Models\ImsItemInventory;
use App\Models\ImsItemRepairLog;
use App\Models\ImsItemType;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Laravel\Facades\Image;

class Lists extends Controller
{
    public function dt(Request $rq)
    {

        $filter_category = $rq->filter_category != 'all' ? Crypt::decrypt($rq->filter_category) : false;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_location = $rq->filter_location != 'all' ? Crypt::decrypt($rq->filter_location) : false;
        $id = $rq->id?Crypt::decrypt($rq->id):'';

        $data = ImsItemInventory::with('item_type')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->when($filter_location,function($q) use($filter_location){
            $q->where('company_location_id',$filter_location);
        })
        ->when($filter_category,function($q) use($filter_category){
            $q->where('item_type_id',$filter_category);
        })
        // ->when($filter_item,function($q) use($filter_item){
        //     $q->whereHas('item_type',function($q2) use($filter_item){
        //         $q2->where('display_to',$filter_item);
        //     });
        // })
        ->where([['is_deleted',null]])
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

            $received_by_emp = optional($item->received_by_emp)->fullname();
            $received_at = Carbon::parse($item->received_at)->format('M d, Y') ?? '--';

            $name = $item->name;
            $description = $item->description;
            $item_type = $item->item_type_id;
            $enable_quick_actions = $item->item_type->display_to == 1 ? true:false ;

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
                // . ($item->item_type_id == 8 ? 'Serial Number: ' . (isset($array['serial_number'])? $array['serial_number']:($item->serial_number??'--')) . '<br>' : '')
                . '</div>';
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_update_at = $last_update_at;

            $item->received_date = $received_at;
            $item->received_by = $received_by_emp;

            $item->name =  $name ?? $description;
            $item->location =  $item->company_location->name;
            $item->description = $description;
            $item->item_number = $item->item_type->item_number ??'';
            $item->item_name = $item->item_type->name ?? '--';
            $item->enable_quick_actions = $enable_quick_actions;
            $item->tag_number = $item->generate_tag_number();
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

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            $item_id = Crypt::decrypt($rq->item);
            $company_location_id = Crypt::decrypt($rq->company_location);
            $created_by = Auth::user()->emp_id;

            $query = ImsItem::find($item_id);

            $description = $query->description;
            if($query->item_type_id == 8){
                $description = json_decode($description,true);
                $description['brand'] = $query->item_brand->name;
                $description['serial_number'] = $rq->serial_number;
                $description = json_encode($description);
            }
            $create = [
                'item_brand_id'=> $query->item_brand_id,
                'item_type_id'=> $query->item_type_id,
                'company_location_id'=>$company_location_id,
                'name'=> $query->name,
                // 'tag_number'=> $rq->tag_number,
                'description'=> $description,
                'price'=> $query->price,
                'serial_number'=> $rq->serial_number,
                'received_at'=> Carbon::createFromFormat('m-d-Y',$rq->received_at)->format('Y-m-d'),
                'warranty_end_at'=> isset($rq->warranty_end_at)?Carbon::createFromFormat('m-d-Y',$rq->warranty_end_at)->format('Y-m-d'):null,
                'received_by'=> Crypt::decrypt($rq->received_by),
                'supplier_id'=> isset($rq->supplier) ? Crypt::decrypt($rq->supplier):null,
                'remarks'=> $rq->remarks,
                'status'=> $rq->status,
                'created_by'=> $created_by,
            ];

            ImsItemInventory::insert($create);
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>'New Inventory is saved']);
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

            $query = ImsItemInventory::find($id);
            $query->status = 0;
            $query->is_deleted = 1;
            $query->remarks = $rq->remarks;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Item is removed',
                'payload' => ImsItemInventory::where('is_deleted',null)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function download_qr(Request $rq)
    // {
    //     try{
    //         $id = Crypt::decrypt($rq->encrypted_id);
    //         $item = ImsItemInventory::findOrFail($id);
    //         $text_below = $item->generate_tag_number();

    //         $url = 'http://156.67.221.153/qr/'.base64_encode($id);

    //         $qrPng = QrCode::format('png')->size(300)->margin(1)->generate($url);
    //         $qrImage = Image::read('data:image/png;base64,' . base64_encode($qrPng));

    //         $canvasHeight = $qrImage->height() + 70;
    //         $canvas = Image::create($qrImage->width(), $canvasHeight)->fill('#ffffff'); // white background

    //         $canvas->place($qrImage, 'top');
    //         $canvas->text($text_below, $canvas->width() / 2, $qrImage->height() + 20, function ($font) {
    //             // Optional: Set font file if needed
    //             $font->filename(public_path('assets/font/Roboto-Bold.ttf'));
    //             $font->size(18);
    //             $font->color('#000000');
    //             $font->align('center');
    //             $font->valign('top');
    //         });

    //         $filename = $text_below . '_QR.png';
    //         return response($canvas->toPng())
    //         ->header('Content-Type', 'image/png')
    //         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    //     }catch(Exception $e){
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }

    // }

    public function download_qr(Request $rq)
    {
        try {
            $id = Crypt::decrypt($rq->encrypted_id);
            $item = ImsItemInventory::findOrFail($id);
            $text_below = $item->generate_tag_number();
            $url = 'http://156.67.221.153/qr/' . base64_encode($id);

            $qrSize = 300;
            $barHeight = 60;
            $textMargin = 10;
            $sidePadding = 40; // ADD this for left and right space

            $qrPng = QrCode::format('png')->size($qrSize)->margin(1)->generate($url);
            $qrImage = Image::read('data:image/png;base64,' . base64_encode($qrPng));

            $qrWidth = $qrImage->width();
            $qrHeight = $qrImage->height();

            $canvasWidth = $qrWidth + ($sidePadding * 2); // wider canvas
            $canvasHeight = $qrHeight + ($barHeight * 2) + 40;

            $canvas = Image::create($canvasWidth, $canvasHeight)->fill('#ffffff');

            // Top Green Bar
            $canvas->drawRectangle(0, 0, function ($rectangle) use ($canvasWidth, $barHeight) {
                $rectangle->size($canvasWidth, $barHeight);
                $rectangle->background('#28a745');
            });

            // Bottom Green Bar
            $canvas->drawRectangle(0, $canvasHeight - $barHeight, function ($rectangle) use ($canvasWidth, $barHeight) {
                $rectangle->size($canvasWidth, $barHeight);
                $rectangle->background('#28a745');
            });

            // Place QR in the center horizontally, just below top bar
            $canvas->place($qrImage, 'top-left', $sidePadding, $barHeight);

            // Top Bar Text
            $canvas->text('PROPERTY OF RVL MOVERS CORPORATION', $canvasWidth / 2, $barHeight / 2, function ($font) {
                $font->filename(public_path('assets/font/Roboto-Bold.ttf'));
                $font->size(18);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });

            // Bottom Bar Text
            $canvas->text('DO NOT REMOVE', $canvasWidth / 2, $canvasHeight - ($barHeight / 2), function ($font) {
                $font->filename(public_path('assets/font/Roboto-Bold.ttf'));
                $font->size(18);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });

            // Tag number below QR
            $canvas->text($text_below, $canvasWidth / 2, $barHeight + $qrHeight + $textMargin, function ($font) {
                $font->filename(public_path('assets/font/Roboto-Bold.ttf'));
                $font->size(18);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });

            $filename = $text_below . '_QR.png';

            return response($canvas->toPng())
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function repair_info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = ImsItemRepairLog::where([['item_inventory_id',$id],['status',1]])->first();
            $payload = [
                'repair_type' =>$query->repair_type,
                'start_at' =>Carbon::parse($query->start_at)->format('m-d-Y'),
                'end_at' => $query->end_at?Carbon::parse($query->end_at)->format('m-d-Y'):null,
                'description' =>$query->description,
                'status' =>$query->status,
                'encrypted_id' =>Crypt::encrypt($query->id),
            ];
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update_repair(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $inventory_item_id = Crypt::decrypt($rq->item_inventory_id);
            $query = ImsItemInventory::find($inventory_item_id);

            $attribute = [
                'id'=>$id,
                'item_inventory_id' => $inventory_item_id,
            ];
            $values = [
                'issued_by' =>$user_id,
                'repair_type' =>$rq->repair_type,
                'item_inventory_status' =>$query->status,
                'start_at' =>Carbon::createFromFormat('m-d-Y',$rq->start_at)->format('Y-m-d'),
                'end_at' => isset($rq->end_at)? Carbon::createFromFormat('m-d-Y',$rq->end_at)->format('Y-m-d'):null,
                'description' =>$rq->description,
                'status' =>$rq->status,
            ];
            if(!isset($id)){
                $values['created_by'] = $user_id;
            }else{
                $values['updated_by'] = $user_id;
            }
            $query = ImsItemRepairLog::updateOrCreate($attribute,$values);
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

    public function generate_report(Request $rq)
    {
        try{



        }catch(Exception $e){

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
