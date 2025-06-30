<?php

namespace App\Http\Controllers\EmployeeController\Reports;

use App\Exports\DevicePerDepartmentReport;
use App\Http\Controllers\Controller;
use App\Models\HrisDepartment;
use App\Models\ImsAccountability;
use App\Models\ImsItemInventory;
use App\Models\ImsItemType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DevicePerDepartment extends Controller
{
    public function dt(Request $rq)
    {
        $departments = HrisDepartment::where('is_active',1)->pluck('name', 'id');

        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $accountabilities = ImsAccountability::with(['accountability_item','issued_to'])->where([['status',1],['is_deleted',null]])->get();
        $report = [];
        $totals = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'Total' => 0,
        ];

        foreach ($departments as $dept_id => $dept_name) {
            $report[$dept_name] = [
                'Laptop' => 0,
                'Desktop' => 0,
                'Cellphone' => 0,
                'Printer' => 0,
                'Total' => 0,
            ];
        }
        foreach ($accountabilities as $acc) {
            foreach ($acc->issued_to as $issued) {
                if($issued->status ==2){ continue; }
                $deptId = $issued->department_id ?? null;
                $deptName = $departments[$deptId] ?? null;

                if (!$deptName) continue;

                foreach ($acc->accountability_item as $item) {
                    if ($item->status != 1) continue; // Only active items

                    $inventory = $item->item_inventory;
                    if (!$inventory) continue;

                    $typeId = $inventory->item_type_id;
                    $typeName = array_search($typeId, $deviceTypes);

                    if ($typeName) {
                        $report[$deptName][$typeName]++;
                        $report[$deptName]['Total']++;

                        $totals[$typeName]++;
                        $totals['Total']++;
                    }
                }
            }
        }

        $render = view('employee.pages.reports.elements.tbl_devices_per_department',compact('report','totals'))->render();
        $payload = base64_encode(json_encode($render));

        return response()->json(['status' => 'success', 'message'=>'success','payload'=>$payload]);
    }


    public function export(Request $rq)
    {

        $departments = HrisDepartment::where('is_active',1)->pluck('name', 'id');

        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $accountabilities = ImsAccountability::with(['accountability_item','issued_to'])->where([['status',1],['is_deleted',null]])->get();
        $report = [];
        $totalValuePerType = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'Total' => 0,
        ];

        foreach ($departments as $dept_id => $dept_name) {
            $report[$dept_name] = [
                'Laptop' => 0,
                'Desktop' => 0,
                'Cellphone' => 0,
                'Printer' => 0,
                'Total' => 0,
            ];
        }

        foreach ($accountabilities as $acc) {
            foreach ($acc->issued_to as $issued) {
                $deptId = $issued->department_id ?? null;
                $deptName = $departments[$deptId] ?? null;

                if (!$deptName) continue;

                foreach ($acc->accountability_item as $item) {
                    if ($item->status != 1) continue; // Only active items

                    $inventory = $item->item_inventory;
                    if (!$inventory) continue;

                    $typeId = $inventory->item_type_id;
                    $typeName = array_search($typeId, $deviceTypes);

                    if ($typeName) {
                        $report[$deptName][$typeName]++;
                        $report[$deptName]['Total']++;

                        $totalValuePerType[$typeName]++;
                        $totalValuePerType['Total']++;
                    }
                }
            }
        }

        $export = new DevicePerDepartmentReport($report, $totalValuePerType);
        $filename = 'device_per_department_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export,$filename);
    }
}
