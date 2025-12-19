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
            1 => 'Available',
            2 => 'Issued',
            3 => 'Temporary Issued',
            4 => 'Under Repair',
            5 => 'Under Warranty',
            6 => 'Deployed',
        ];

        // Return only the fields you want in Excel
        return $this->data->map(function ($item) use ($statusMap) {
            return [
                'Tag Number'            => $item->tag_number,
                'Name'                  => strip_tags($item->name),
                'Type'                  => $item->item_type_name,
                'Description'           => strip_tags($item->description),
                'Serial Number'         => $item->serial_number,
                'Location'              => $item->location,
                'Price'                 => $item->price,
                'Purchased Date'        => $item->received_at,
                'Status'                => $statusMap[$item->status] ?? 'Unknown',
                'Remarks'               => $item->remarks,
                'Accountability No.'    => $item->form_no,
                'Accountable To'        => $item->accountable_to,
                'Inventory By'          => $item->created_by_name,
                'Last Updated By'       => $item->updated_by_name,
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
            'Location',
            'Price',
            'Purchased Date',
            'Status',
            'Remarks',
            'Accountability No. ',
            'Accountable To',
            'Inventory By',
            'Last Updated By',
        ];
    }
}
