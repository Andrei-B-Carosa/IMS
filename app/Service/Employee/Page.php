<?php

namespace App\Service\Employee;

use App\Models\ImsAccountability;
use App\Service\Select\EmployeeOptions;
use App\Service\Select\InventoryOption;
use App\Service\Select\ItemOption;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Page
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function accountability_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = ImsAccountability::with('issued_by_emp')->withCount([
                'accountability_item as accountability_item_status_1_count' => function ($query) {
                    $query->where('status', 1);
                },
                'accountability_item as accountability_item_status_2_count' => function ($query) {
                    $query->where('status', 2);
                },
                'issued_to as issued_to_status_1_count' => function ($query) {
                    $query->where('status', 1);
                },
            ])->find($id);

            $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accessories']);
            $other_accessories = (new ItemOption)->list($rq);

            return view('employee.pages.accountability.details', compact('data','other_accessories'))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function new_accountability($rq)
    {

        $issued_by_option = '<option value="'.Crypt::encrypt(Auth::user()->emp_id).'">'.Auth::user()->employee->fullname().'</option>';

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'accountability_items']);
        $inventory_options = (new InventoryOption)->list($rq);

        $rq = $rq->merge(['id' => null, 'view'=>'1', 'type'=>'employee']);
        $employee_option = (new EmployeeOptions)->list($rq);


        return view('employee.pages.accountability.new_accountability',compact('inventory_options','issued_by_option','employee_option'))->render();
    }

}
