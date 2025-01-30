<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class EmployeeAccount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'emp_id',
        'username',
        'password',
        'bypass_key',
        'c_email',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'bypass_key',
    ];

    public static function createAccount($employee,$user_id)
    {
        $hashedPassword = Hash::make($employee->emp_no);
        $username = strtolower($employee->fname.'.'.$employee->lname);
        $c_email = $username.'@'.config('company.company_domain');

        $existingAccount = EmployeeAccount::where([['emp_id', $employee->id], ['is_active', 1]])->first();
        if (!$existingAccount) {
            EmployeeAccount::create([
                'emp_id' => $employee->id, // Assuming $employee->id exists
                'username' => $username,
                'password' => $hashedPassword,
                'c_email' =>$c_email,
                'bypass_key' => self::generateUniqueBypassKey(),
                'is_active' => 1,
                'created_by'=>$user_id
            ]);

            HrisUserRole::create([
                'emp_id' => $employee->id,
                'role_id' => 2,
                'is_active' => 1,
                'created_by'=>$user_id
            ]);

            return true;
        }
        return false;
    }

    public static function generateUniqueBypassKey()
    {
        do {
            // Generate a random 6-digit number
            $bypassKey = mt_rand(100000, 999999);

            // Check if this key already exists in the database
            $exists = DB::table('employee_accounts')->where('bypass_key', $bypassKey)->exists();

        } while ($exists); // Repeat until a unique key is generated
        return $bypassKey;
    }

    public function user_roles(){
       return $this->hasOne(HrisUserRole::class,'emp_id','emp_id');
    }

    public function employee(){
        return $this->hasOne(Employee::class,'id','emp_id');
    }

    public function employee_details(){
        return $this->hasOne(HrisEmployeePosition::class,'emp_id','emp_id');
    }
}
