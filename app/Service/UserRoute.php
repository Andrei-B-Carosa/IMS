<?php

namespace App\Service;

use App\Models\ImsRole;
use App\Models\ImsRoleAccess;
use App\Models\ImsSystemFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserRoute
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getWebRoutes($role_id)
    {
        try {
            $role = ImsRole::find($role_id);
            $role_name = strtolower($role->name);
            return Cache::rememberForever(''.$role_name.'_routes', function () use($role) {
                $file_id = ImsRoleAccess::with('')->where('role_id',$role->id)->pluck('file_id');
                return ImsSystemFile::with([
                    'file_layer'=>function($q){
                        $q->where('status',1);
                    }
                ])->whereIn('id',$file_id)->where('status',1)->get();
            });
        } catch (\Exception $e) {

            Log::error('Error retrieving '.$role->name.' routes: ' . $e->getMessage());
            return array();
        }
    }
}
