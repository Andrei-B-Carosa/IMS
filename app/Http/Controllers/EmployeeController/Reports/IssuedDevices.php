<?php

namespace App\Http\Controllers\EmployeeController\Reports;

use App\Exports\IssuedDeviceExport;
use App\Http\Controllers\Controller;
use App\Models\ImsAccountabilityItem;
use App\Models\ImsItemInventory;
use App\Models\ImsStoredProcedure;
use App\Service\Reusable\Datatable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class IssuedDevices extends Controller
{
    public function sp_get_inventory_accountability($rq)
    {
        $filter_status = $rq->filled('filter_status') && $rq->filter_status != 'all' ? $rq->filter_status : null;
        $filter_location = $rq->filter_location &&  $rq->filter_location != 'all' ? Crypt::decrypt($rq->filter_location) : false;
        $filter_category = $rq->filter_category &&  $rq->filter_category != 'all' ? Crypt::decrypt($rq->filter_category) : false;
        $filter_year = $rq->filter_year && $rq->filter_year != 'all' ? $rq->filter_year : false;
        $filter_month = $rq->filter_month && $rq->filter_month != 'all' ? $rq->filter_month : false;
        $data = ImsStoredProcedure::sp_get_inventory_accountability(
            $filter_status,
            $filter_location,
            $filter_year,
            (isset($rq->is_consumable)? 1:0),
            $filter_category,
            $filter_month
        );
        return $data;
    }

    public function dt(Request $rq)
    {
        $data = $this->sp_get_inventory_accountability($rq);
        $data->transform(function ($item, $key) {
            $item_type = $item->item_type_id;
            $description = $item->description;
            $enable_quick_actions = $item->display_to == 1 ? true:false ;
            $received_at = Carbon::parse($item->received_at)->format('M d, Y') ?? '--';

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
            $item->enable_quick_actions = $enable_quick_actions;

            $item->description = $description;
            $item->location =  $item->company_location;
            $item->received_at =  $received_at;
            $item->accountable_to = $item->issued_to_names;

            $item->encrypted_id = Crypt::encrypt($item->id);
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

    public function export(Request $rq)
    {
        $search = trim($rq->search);
        $data = $this->sp_get_inventory_accountability($rq);
        if ($search) {
            $data = $data->filter(function ($item) use ($search) {
                $search = strtolower($search);

                return str_contains(strtolower($item->name), $search)
                    || str_contains(strtolower($item->serial_number), $search)
                    || str_contains(strtolower($item->tag_number), $search)
                    || str_contains(strtolower($item->issued_to_names ?? ''), $search)
                    || str_contains(strtolower(strip_tags($item->description)), $search);
            })->values();
        }
        $data->transform(function ($item, $key) {
            $item_type = $item->item_type_id;
            $description = $item->description;
            $received_at = Carbon::parse($item->received_at)->format('M d, Y') ?? '--';
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
            $item->location =  $item->company_location;
            $item->received_at =  $received_at;
            $item->accountable_to = $item->issued_to_names;
            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });
        $filename = 'issued_devices_' . now()->format('Ymd_His') . '.csv';
        return Excel::download(new IssuedDeviceExport($data), $filename, \Maatwebsite\Excel\Excel::CSV);

    }
}
