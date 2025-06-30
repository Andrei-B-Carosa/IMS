<?php

namespace App\Http\Controllers\EmployeeController\Reports;

use App\Exports\DevicePerSiteReport;
use App\Http\Controllers\Controller;
use App\Models\HrisCompanyLocation;
use App\Models\ImsItemInventory;
use App\Models\ImsItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DevicePerSite extends Controller
{
    public function dt(Request $rq)
    {
        $sites = HrisCompanyLocation::where('is_active',1)->pluck('name', 'id');

        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $inventory = ImsItemInventory::select('company_location_id', 'item_type_id', DB::raw('COUNT(*) as total'))
            ->whereIn('item_type_id', array_values($deviceTypes))
            ->groupBy('company_location_id', 'item_type_id')
            ->where([['status','!=',0],['is_deleted',null]])
            ->get();

        $report = [];
        $totals = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'Total' => 0,
        ];

        foreach ($sites as $site_id => $site_name) {
            $report[$site_name] = [
                'Laptop' => 0,
                'Desktop' => 0,
                'Cellphone' => 0,
                'Printer' => 0,
                'Total' => 0,
            ];
        }

        foreach ($inventory as $row) {
            $siteName = $sites[$row->company_location_id] ?? 'Unknown';
            $deviceType = array_search($row->item_type_id, $deviceTypes);

            if ($siteName && $deviceType) {
                $report[$siteName][$deviceType] += $row->total;
                $report[$siteName]['Total'] += $row->total;

                $totals[$deviceType] += $row->total;
                $totals['Total'] += $row->total;
            }
        }

        $render = view('employee.pages.reports.elements.tbl_devices_per_site',compact('report','totals'))->render();
        $payload = base64_encode(json_encode($render));

        return response()->json(['status' => 'success', 'message'=>'success','payload'=>$payload]);
    }

    public function export(Request $rq)
    {

        $sites = HrisCompanyLocation::where('is_active',1)->pluck('name', 'id');

        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $inventory = ImsItemInventory::select('company_location_id', 'item_type_id', DB::raw('COUNT(*) as total'))
            ->whereIn('item_type_id', array_values($deviceTypes))
            ->groupBy('company_location_id', 'item_type_id')
            ->get();

        $report = [];
        $totalValuePerType = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'Total' => 0,
        ];

        foreach ($sites as $site_id => $site_name) {
            $report[$site_name] = [
                'Laptop' => 0,
                'Desktop' => 0,
                'Cellphone' => 0,
                'Printer' => 0,
                'Total' => 0,
            ];
        }

        foreach ($inventory as $row) {
            $siteName = $sites[$row->company_location_id] ?? 'Unknown';
            $deviceType = array_search($row->item_type_id, $deviceTypes);

            if ($siteName && $deviceType) {
                $report[$siteName][$deviceType] += $row->total;
                $report[$siteName]['Total'] += $row->total;

                $totalValuePerType[$deviceType] += $row->total;
                $totalValuePerType['Total'] += $row->total;
            }
        }

        $export = new DevicePerSiteReport($report, $totalValuePerType);
        $filename = 'device_per_site_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export,$filename);
    }
}
