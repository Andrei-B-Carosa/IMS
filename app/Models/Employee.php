<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_no',
        'fname',
        'lname',
        'mname',
        'ext',
        'title',
        'mobile_no',
        'birthday',
        'birthplace',
        'sex',
        'civil_status',
        'telephone_number',
        'mobile_number',
        'p_email',
        'citizenship',
        'height',
        'weight',
        'blood_type',
        'gsis',
        'pagibig',
        'philhealth',
        'sss',
        'tin',
        'agency',
        'is_active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'spouse_fname',
        'spouse_lname',
        'spouse_mname',
        'spouse_occupation',
        'spouse_employer',
        'spouse_business_address',
        'father_fname',
        'father_lname',
        'father_mname',
        'father_ext',
        'mother_fname',
        'mother_lname',
        'mother_mname',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate employee ID before creating a new employee record
        static::creating(function ($model) {
               $model->emp_no = self::generateEmployeeId($model);
        });
    }

    public static function generateEmployeeId($employee)
    {
        $date = Carbon::now();
        $lastRecord = self::whereYear('created_at', $date->format('Y'))->count();
        $lastTwoDigitsOfYear = $date->format('y');
        $employee_birthyear = Carbon::parse($employee->birthday)->format('ym');
        $filler = str_pad($lastRecord + 1, 4, '0', STR_PAD_LEFT);
        $ID = $lastTwoDigitsOfYear . $employee_birthyear . $filler;

        return $ID;
    }

    public function emp_details()
    {
        return $this->hasOne(HrisEmployeePosition::class,'emp_id')->withDefault();
    }

    public function emp_account()
    {
        return $this->hasOne(EmployeeAccount::class,'emp_id');
    }

    public function documents()
    {
        return $this->HasMany(HrisEmployeeDocument::class,'emp_id');
    }

    public function fullname()
    {
        return $this->fname.' '.$this->lname;
    }

    public function emp_leave_balance()
    {
        return $this->hasMany(HrisEmployeeLeaveBalance::class,'emp_id');
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(self::class,'updated_by')->withDefault();
    }

    public function created_by_emp()
    {
        return $this->belongsTo(self::class,'created_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(self::class,'deleted_by');
    }

    public function emp_position()
    {
        return $this->hasOne(HrisEmployeePosition::class,'emp_id')->withDefault();
    }

    public function group_member()
    {
        return $this->hasMany(HrisGroupMember::class,'emp_id');
    }

    public function group_approver()
    {
        return $this->hasMany(HrisGroupApprover::class,'emp_id');
    }

    public function user_roles()
    {
        return $this->hasMany(HrisUserRole::class,'emp_id');
    }
}
