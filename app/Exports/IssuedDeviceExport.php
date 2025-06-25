<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class IssuedDeviceExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $statusMap = [
            0 => 'Disposed',
            1 => 'Issued',
            2 => 'Returned',
            3 => 'Temporary Issued',
            4 => 'Under Repair',
        ];

        // Return only the fields you want in Excel
        return $this->data->map(function ($item) use ($statusMap) {
            return [
                'Tag Number'            => $item->tag_number,
                'Name'                  => strip_tags($item->name),
                'Type'                  => $item->type,
                'Description'           => strip_tags($item->description),
                'Serial Number'         => $item->serial_number,
                'Price'                 => $item->price,
                'Accountability No.'    => $item->form_no,
                'Status'                => $statusMap[$item->accountability_status] ?? 'Unknown',
                'Issued At'             => $item->issued_at,
                'Returned At'           => $item->returned_at,
                'Accountable To'        => $item->accountable_to,
                'Remarks'               => $item->remarks,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tag Number',
            'Name',
            'Type',
            'Description',
            'Serial Number',
            'Price',
            'Accountability No. ',
            'Status',
            'Issued At',
            'Returned At',
            'Accountable To',
            'Remarks',
        ];
    }
}
