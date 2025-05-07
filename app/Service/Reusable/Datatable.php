<?php

namespace App\Service\Reusable;

class Datatable
{
    protected $request;
    protected $data;
    public $rows;
    public $draw;
    public $recordsTotal;
    public $recordsFiltered;

    public function __construct($request, $data) {
        $this->request = $request;
        $this->data = $data;
    }

    public function renderTable()
    {
        $draw = $this->request->get('draw');
        $start = $this->request->get('start');
        $length = $this->request->get('length');
        $search = $this->request->get('search')['value'];
        $order_column = $this->request->get('order')[0]['column'];
        $order_dir = $this->request->get('order')[0]['dir'];
        $columns = $this->request->get('columns');

        $results = $this->data;
        // Apply search filter
        if ($search) {
            $results = $results->filter(function ($row) use ($search, $columns) {
                $searchable_columns = array_filter($columns, function($column) {
                    return $column['searchable'] == 'true';
                });

                foreach ($searchable_columns as $column) {
                    $column_name = $column['name'];
                    if (stripos($row->$column_name, $search) !== false) {
                        return true;
                    }
                }

                return false;
            });
        }

        // Apply order by
        $order_column_name = $this->request->get('columns')[$order_column]['data'];
        $results = $results->sortBy($order_column_name, SORT_NATURAL, $order_dir === 'asc');

        $filtered_count = $results->count();

        $paginated_data = $results->skip($start)->take($length)->values();
        // Create an empty array to hold the data
        $data = [];

        // Iterate over the paginated data and add the columns to the data array
        foreach ($paginated_data as $row) {
            $data_row = [];

            foreach ($columns as $column) {
                // Get the name of the column
                $column_name = $column['name'];

                // Add the column data to the row
                $data_row[$column_name] = $row->$column_name;
            }

            // Add the row to the data array
            $data[] = $data_row;
        }
        // Set the class properties to the updated values
        $this->rows = $data;
        $this->draw = $draw;
        $this->recordsTotal = $filtered_count;
        $this->recordsFiltered = $filtered_count;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getDraw()
    {
        return $this->draw;
    }

    public function getRecordsTotal()
    {
        return $this->recordsTotal;
    }

    public function getRecordsFiltered()
    {
        return $this->recordsFiltered;
    }
}
