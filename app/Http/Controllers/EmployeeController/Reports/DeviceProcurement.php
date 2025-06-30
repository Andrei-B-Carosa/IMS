<?php

namespace App\Http\Controllers\EmployeeController\Reports;

use App\Http\Controllers\Controller;
use App\Models\ImsItemInventory;
use App\Models\ImsItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\DeviceProcurementReport;
use Maatwebsite\Excel\Facades\Excel;

class DeviceProcurement extends Controller
{
    public function dt(Request $rq)
    {
        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $totalValuePerType = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'GrandTotalValue' => 0,
        ];

        $filter_year = $rq->filter_year;
        $inventory = ImsItemInventory::select(
            DB::raw('YEAR(received_at) as year'),
            DB::raw('MONTH(received_at) as month'),
            'item_type_id',
            DB::raw('COUNT(*) as qty'),
            DB::raw('SUM(price) as total_value'),
        )
        ->whereIn('item_type_id', array_values($deviceTypes))
        ->when($filter_year =='all', fn($q) => $q->whereYear('received_at', '>=', 2025))
        ->when($filter_year !='all', fn($q) => $q->whereYear('received_at', '==', $filter_year))
        ->groupBy(DB::raw('YEAR(received_at)'), DB::raw('MONTH(received_at)'), 'item_type_id')
        ->orderBy('month')
        ->orderBy('year')
        ->get();

        $report = [];

        foreach ($inventory as $row) {
        $monthKey = Carbon::create($row->year, $row->month)->format('F Y');
            if (!isset($report[$monthKey])) {
                $report[$monthKey] = [
                    'Laptop' => 0,
                    'Desktop' => 0,
                    'Cellphone' => 0,
                    'Printer' => 0,
                    'TotalQty' => 0,
                    'TotalValue' => 0,
                ];
            }
            $typeName = array_search($row->item_type_id, $deviceTypes);
            if ($typeName) {
                $report[$monthKey][$typeName] += $row->qty;
                $report[$monthKey]['TotalQty'] += $row->qty;
                $report[$monthKey]['TotalValue'] += $row->total_value;

                // Track value per device type
                $totalValuePerType[$typeName] += $row->total_value;
                $totalValuePerType['GrandTotalValue'] += $row->total_value;
            }
        }

        uksort($report, fn($a, $b) => strtotime($a) <=> strtotime($b));
        $render = view('employee.pages.reports.elements.tbl_device_procurement',compact('report','totalValuePerType'))->render();
        $payload = base64_encode(json_encode($render));

        return response()->json(['status' => 'success', 'message'=>'success','payload'=>$payload]);
    }

    public function export(Request $rq)
    {
        $filter_year = $rq->filter_year == 'all' ? 2025 : $rq->filter_year;

        // Reuse same logic from report
        $deviceTypes = [
            'Laptop' => ImsItemType::getLaptopId(),
            'Desktop' => ImsItemType::getDesktopId(),
            'Cellphone' => ImsItemType::getCellphoneId(),
            'Printer' => ImsItemType::getPrinterId(),
        ];

        $inventory = ImsItemInventory::select(
                DB::raw('YEAR(received_at) as year'),
                DB::raw('MONTH(received_at) as month'),
                'item_type_id',
                DB::raw('COUNT(*) as qty'),
                DB::raw('SUM(price) as total_value')
            )
        ->whereIn('item_type_id', array_values($deviceTypes))
        ->when($filter_year =='all', fn($q) => $q->whereYear('received_at', '>=', 2025))
        ->when($filter_year !='all', fn($q) => $q->whereYear('received_at', '==', $filter_year))
        ->groupBy(DB::raw('YEAR(received_at)'), DB::raw('MONTH(received_at)'), 'item_type_id')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $report = [];
        $totalValuePerType = [
            'Laptop' => 0,
            'Desktop' => 0,
            'Cellphone' => 0,
            'Printer' => 0,
            'GrandTotalValue' => 0,
        ];

        foreach ($inventory as $row) {
            $monthKey = \Carbon\Carbon::create($row->year, $row->month)->format('F Y');

            if (!isset($report[$monthKey])) {
                $report[$monthKey] = [
                    'Laptop' => 0,
                    'Desktop' => 0,
                    'Cellphone' => 0,
                    'Printer' => 0,
                    'TotalQty' => 0,
                    'TotalValue' => 0,
                ];
            }

            $typeName = array_search($row->item_type_id, $deviceTypes);

            if ($typeName) {
                $report[$monthKey][$typeName] += $row->qty;
                $report[$monthKey]['TotalQty'] += $row->qty;
                $report[$monthKey]['TotalValue'] += $row->total_value;

                $totalValuePerType[$typeName] += $row->total_value;
                $totalValuePerType['GrandTotalValue'] += $row->total_value;
            }
        }

        uksort($report, fn($a, $b) => strtotime($a) <=> strtotime($b));
        $export = new DeviceProcurementReport($report, $totalValuePerType);
        $filename = 'device_procurement_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export,$filename);
    }
}
