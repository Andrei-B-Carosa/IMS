<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DevicePerDepartmentReport implements FromView
{
    protected $report;
    protected $totalValuePerType;

    public function collection()
    {
        //
    }

    public function __construct($report, $totalValuePerType)
    {
        $this->report = $report;
        $this->totalValuePerType = $totalValuePerType;
    }

    public function view(): View
    {
        return view('employee.pages.reports.elements.tbl_devices_per_department', [
            'report' => $this->report,
            'totals' => $this->totalValuePerType,
        ]);
    }
}
