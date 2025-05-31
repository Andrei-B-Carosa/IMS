<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\AccessController\EmployeeLogin;
use App\Http\Controllers\Controller;
use App\Models\HrisEmployeePosition;
use App\Models\ImsRoleAccess;
use App\Models\ImsSystemFile;
use App\Models\ImsSystemFileLayer;
use App\Models\ImsUserRole;
use App\Service\Employee\Page as EmployeePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Page extends Controller
{
    public function system_file(Request $rq)
    {
        $user_id = Auth::user()->emp_id;
        $user_role = ImsUserRole::where([['role_id',2],['emp_id',$user_id],['is_active',1]])->first();

        if(!$user_role){
            if (session()->has('user_id')) {
                $role = session('user_role');
                $default = session('default');
                return redirect("$default");
            }else{
                (new EmployeeLogin)->logout($rq);
            }
        }

        if(!$user_role->is_active || !$user_role)
        {
            (new EmployeeLogin)->logout($rq);
        }

        $query = ImsRoleAccess::with(['system_file_layer','system_file'])
        ->where([['is_active',1],['role_id',$user_role->role_id]])->orderBy('file_order')->get();
        if(!$query)
        {
            (new EmployeeLogin)->logout($rq);
        }

        $result = [];
        foreach($query as $data)
        {
            $file = $data->system_file;
            if($file->status == 0){
                continue;
            }

            $file_layer = [];
            $layer = $data->system_file_layer;
            if($layer){
                foreach($layer as $row)
                {
                    if($row->status ==0){
                        continue;
                    }
                    $file_layer[]=[
                        'name'=>$row->system_layer->name,
                        'href'=>$row->system_layer->href,
                        'icon'=>$row->system_layer->icon,
                    ];
                }
            }

            $result[]=[
                'name'=>$file->name,
                'href'=>$file->href,
                'icon'=>$file->icon,
                'file_layer'=>$file_layer,
            ];
        }
        return view('employee.layout.app',compact('result'));
    }

    public function setup_page(Request $rq)
    {
        $page = new EmployeePage;
        $role = 'employee';

        $rq->session()->put($role . '_page', $rq->page ?? 'dashboard');
        $view = $rq->session()->get($role . '_page');

        $pages = [
            'accountability-details' => fn() => $page->accountability_details($rq),
            'new-accountability' => fn() => $page->new_accountability($rq),
            'inventory-details' => fn() => $page->inventory_details($rq),
            'new-inventory' => fn() => $page->new_inventory($rq),
            'new-material-issuance' => fn() => $page->new_material_issuance($rq),
            'material-issuance-details' => fn() => $page->material_issuance_details($rq),
            'item-details' => fn() => $page->item_details($rq),
            'new-item' => fn() => $page->new_item($rq),
        ];

        if (array_key_exists($view, $pages)) {
            return response(['page' => $pages[$view]()], 200);
        }

        $row = ImsSystemFile::with(['file_layer' => function ($query) use ($view) {
            $query->whereHas('system_layer', function ($q) use ($view) {
                $q->where([['status', 1],['href', $view]]);
            })
            ->with(['system_layer' => function ($q) use ($view) {
                $q->where([['status', 1],['href', $view]]);
            }]);
        }])
        ->where(function ($query) use ($view) {
            $query->where([['status', 1],['href', $view]])
            ->orWhereHas('file_layer.system_layer', function ($q) use ($view) {
                $q->where([['status', 1],['href', $view]]);
            });
        })
        ->first();

        if (!$row || !$row->file_layer) {
            return view("$role.not_found");
        }
        $folders = !$row->file_layer->isEmpty()
            ? $row->folder.'.'.$row->file_layer[0]->system_layer->folder
            : $row->folder;
        $file = $row->file_layer[0]->system_layer->href ?? $row->href;

        return response(['page' => view("$role.pages.$folders.$file")->render()], 200);
    }

}
