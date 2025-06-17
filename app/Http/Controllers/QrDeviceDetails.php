<?php

namespace App\Http\Controllers;

use App\Models\ImsItemInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QrDeviceDetails extends Controller
{
    public function device_details($id)
    {
        $id = base64_decode($id);
        $query = ImsItemInventory::find($id);
        if(!$query){
            return view('guest.qr_not_found');
        }

        $description = $query->description;
        $item_type = $query->item_type_id;

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
            . ($item_type == 8 ? 'Model: ' . $array['model'] . '<br>' : '')
            . 'CPU: ' . $array['cpu'] . '<br>'
            . 'RAM: ' . $ram_html . '<br>'
            . $storage_html
            . 'OS: ' . $array['windows_version'] . '<br>'
            . $gpu_html
            . 'Device Name: ' . $array['device_name'] . '<br>'
            . '</div>';
        }

        $statusList = [
            0 => ['warning', 'Disposed'],
            1 => ['info', 'Available'],
            2 => ['success', 'Issued'],
            3 => ['secondary', 'Temporary Issued'],
            4 => ['danger', 'Under Repair'],
        ];

        $status = $statusList[$query->status] ?? ['dark', 'Unknown'];

        $data = [
            'type'=>$query->item_type->name,
            'located_at' =>$query->company_location->name,
            'brand' =>optional($query->brand)->name,
            'name' => $query->name,
            'tag_number' => $query->generate_tag_number(),
            'description'=>$description,
            'serial_number' => $query->serial_number,
            'price' => number_format($query->price, 2),
            'warranty_end_at' => $query->warranty_end_at?Carbon::parse($query->warranty_end_at)->format('m-d-Y'):null,
            'remarks' => $query->remarks ?? '--',
            'status' => $query->status,
            'status_badge' => [
                'class' => $status[0],
                'label' => $status[1],
            ],
        ];

        $accountability_history = self::construct_accountability_history($query);
        $repair_history = self::construct_repair_history($query);

        return view('guest.qr_device_details',compact('data','accountability_history','repair_history'));
    }

    public function construct_accountability_history($query)
    {
        $data = $query->accountability_item;
        $array = [];

        // * 1= Issued
        //   2= Returned
        //   3=
        //   4= Under Repair
        $statusList = [
            1 => ['success', 'Issued'],
            2 => ['info', 'Returned'],
            3 => ['secondary', 'Temporary Issue'],
            4 => ['danger', 'Under Repair'],
        ];

        if($data->isNotEmpty()){
            foreach($data as $row)
            {
                $accountability = $row->accountability;
                $status = $statusList[$row->status] ?? ['dark', 'Unknown'];

                $names = [];
                foreach ($row->accountable_to as $accountable_to) {
                    if ($accountable_to->employee) {
                        $names[] = $accountable_to->employee->fullname();
                    }
                }

                $array[] = [
                    'form_no'=>$accountability->form_no ??'--',
                    'issued_by'=>$accountability->issued_by_emp->fullname(),
                    'issued_at'=>$row->issued_at?Carbon::parse($row->issued_at)->format('m-d-Y'):'--',
                    'returned_at'=>$row->removed_at?Carbon::parse($row->removed_at)->format('m-d-Y'):'--',
                    'remarks'=>$row->remarks ?? '--',
                    'status'=>$row->status,
                    'accountable_to' => implode(', ', $names),
                    'status_badge' => [
                        'class' => $status[0],
                        'label' => $status[1],
                    ],
                ];
            }
        }

        return $array;
    }

    public function construct_repair_history($query)
    {
        $data = $query->repair_log;
        $statusList = [
            1 => ['info', 'In Progress'],
            2 => ['success', 'Resolved'],
            3 => ['danger', 'Not Repairable'],
        ];


        $array = [];

        //  * repair_type
        //  * 1=hardware
        //  * 2=software
        //  *
        //  * 1=in progress
        //  * 2=resolved
        //  * 3=not repairable

        if($data->isNotEmpty()){
            foreach($data as $row)
            {
                $status = $statusList[$row->status] ?? ['dark', 'Unknown'];
                $array[]=[
                    'start_at'=>$row->start_at?Carbon::parse($row->start_at)->format('m-d-Y'):'--',
                    'end_at'=>$row->end_at?Carbon::parse($row->end_at)->format('m-d-Y'):'--',
                    'status'=>$row->status,
                    'repair_type'=>$row->repair_type ==1 ?'Hardware':'Software',
                    'description'=>$row->description,
                    'item_inventory_status'=>$row->item_inventory_status,
                    'initialize_by' =>$row->created_by_emp->fullname(),
                    'status_badge' => [
                        'class' => $status[0],
                        'label' => $status[1],
                    ],
                ];
            }
        }

        return $array;
    }
}
