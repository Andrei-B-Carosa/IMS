<?php

namespace App\Http\Controllers\AccessController;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLogin extends Controller
{
    public function form() :View
    {
        return view('login.employee');
    }

    public function login(Request $rq)
    {
        if(Auth::attempt($rq->only('username','password')))
        {
            $user = Auth::user();
            $user_role = $user->user_roles;
            if(!$user_role)
            {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message'=>'Account Role is not set, Contact MIS',
                    'payload'=>csrf_token()
                ]);
            }
            if(!$user->employee->is_active || !$user->is_active || !$user_role->is_active)
            {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message'=>'Account is Deactivated',
                    'payload'=>csrf_token()
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message'=>'Login Success',
                'payload'=>'/dashboard'
            ]);

        }
        return response()->json([
            'status' => 'error',
            'message'=>'Incorrect username or password',
            'payload'=>csrf_token()
        ]);
    }

    public function logout(Request $rq)
    {
        if(Auth::check())
        {
            Auth::logout();
            session()->flush();
            return redirect()->route('employee.form.login');
        }
    }
}
