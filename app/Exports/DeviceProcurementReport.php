<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeviceProcurementReport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

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
        return view('employee.pages.reports.elements.tbl_device_procurement', [
            'report' => $this->report,
            'totalValuePerType' => $this->totalValuePerType,
        ]);
    }
}
