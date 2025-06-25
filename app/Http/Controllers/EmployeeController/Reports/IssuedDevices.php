<?php

namespace App\Http\Controllers\EmployeeController\Reports;

use App\Exports\IssuedDeviceExport;
use App\Http\Controllers\Controller;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItemInventory;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class IssuedDevices extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $data = ImsItemInventory::with('active_accountability_item.accountable_to')
        ->when($filter_status,function($q) use($filter_status){
            $q->where('status',$filter_status);
        })
        ->where([['status',2],['is_deleted',null]])
        ->get();

        $data->transform(function ($item, $key) {

            $acountability_item = $item->active_accountability_item;

            $issued_at = null;
            if($acountability_item->issued_at != null){
                $issued_at = Carbon::parse($acountability_item->issued_at)->format('M d, Y');
            }

            $returned_at = null;
            if($acountability_item->removed_at != null){
                $returned_at = Carbon::parse($acountability_item->removed_at)->format('M d, Y');
            }

            $array_accountable_to = [];
            $form_no = null;

            foreach($acountability_item->accountable_to as $accountable_to){
                if($accountable_to->status !=1){
                    continue;
                }
                $array_accountable_to[] = $accountable_to->employee->fullname();
                if($accountable_to->accountability){
                    $form_no = $accountable_to->accountability->form_no;
                }
            }

            $description = $item->description;
            $item_type = $item->item_type_id;
            $tag_number = $item->generate_tag_number();

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
                . ($item_type == 8 ? 'Model: ' . $array['model'] . '<br>' : '')
                . 'CPU: ' . $array['cpu'] . '<br>'
                . 'RAM: ' . $ram_html . '<br>'
                . $storage_html
                . 'OS: ' . $array['windows_version'] . '<br>'
                . $gpu_html
                . 'Device Name: ' . $array['device_name'] . '<br>'
                . ($item_type == 8 ? 'Serial Number: ' . (isset($array['serial_number'])? $array['serial_number']:($item->serial_number??'--')) . '<br>' : '')
                . '</div>';
            }

            $item->count = $key + 1;
            $item->issued_at = $issued_at;
            $item->returned_at = $returned_at;

            $item->accountable_to = implode(', ', $array_accountable_to);
            $item->tag_number = $tag_number;
            $item->name =  $item->name ?? $description;
            $item->description = $description;

            $item->serial_number = $item->serial_number;
            $item->price = $item->price;
            $item->type = $item_type;
            $item->form_no = $form_no;
            $item->accountability_status = $acountability_item->status;
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

    public function export(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $data = ImsItemInventory::with('active_accountability_item.accountable_to')
        ->when($filter_status, fn($q) => $q->where('status', $filter_status))
        ->where([['status', 2], ['is_deleted', null]])
        ->get();

        // Transform like you already do
        $data->transform(function ($item, $key) {

            $acountability_item = $item->active_accountability_item;

            $issued_at = null;
            if($acountability_item->issued_at != null){
                $issued_at = Carbon::parse($acountability_item->issued_at)->format('M d, Y');
            }

            $returned_at = null;
            if($acountability_item->removed_at != null){
                $returned_at = Carbon::parse($acountability_item->removed_at)->format('M d, Y');
            }

            $array_accountable_to = [];
            $form_no = null;

            foreach($acountability_item->accountable_to as $accountable_to){
                $array_accountable_to[] = $accountable_to->employee->fullname();
                if($accountable_to->accountability){
                    $form_no = $accountable_to->accountability->form_no;
                }
            }

            $description = $item->description;
            $item_type = $item->item_type_id;
            $tag_number = $item->generate_tag_number();

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
                . ($item_type == 8 ? 'Model: ' . $array['model'] . '<br>' : '')
                . 'CPU: ' . $array['cpu'] . '<br>'
                . 'RAM: ' . $ram_html . '<br>'
                . $storage_html
                . 'OS: ' . $array['windows_version'] . '<br>'
                . $gpu_html
                . 'Device Name: ' . $array['device_name'] . '<br>'
                . ($item_type == 8 ? 'Serial Number: ' . (isset($array['serial_number'])? $array['serial_number']:($item->serial_number??'--')) . '<br>' : '')
                . '</div>';
            }

            $item->count = $key + 1;
            $item->issued_at = $issued_at;
            $item->returned_at = $returned_at;

            $item->form_no = $form_no;
            $item->accountable_to = implode(', ', $array_accountable_to);
            $item->tag_number = $tag_number;
            $item->name =  $item->name ?? $description;
            $item->description = $description;

            $item->serial_number = $item->serial_number;
            $item->price = $item->price;
            $item->type = $item->item_type->name;
            $item->accountability_status = $acountability_item->status;
            $item->encrypted_id = Crypt::encrypt($item->id);

            return $item;
        });

        $filename = 'issued_devices_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new IssuedDeviceExport($data), $filename);
    }
}
