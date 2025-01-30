<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeePosition extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'company_id',
        'company_location_id',
        'department_id',
        'section_id',
        'employment_id',
        'position_id',
        'classification_id',
        'date_employed',
        // 'salary_value',
        'work_status',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

    public function classification()
    {
        return $this->belongsTo(HrisClassification::class,'classification_id')->withDefault();
    }

    public function employment()
    {
        return $this->belongsTo(HrisEmploymentType::class,'employment_id')->withDefault();

    }

    public function section()
    {
        return $this->belongsTo(HrisSection::class,'section_id')->withDefault();
    }

    public function position()
    {
        return $this->belongsTo(HrisPosition::class,'position_id')->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(HrisDepartment::class,'department_id')->withDefault();
    }

    public function company()
    {
        return $this->belongsTo(HrisCompany::class,'company_id')->withDefault();
    }

    public function company_location()
    {
        return $this->belongsTo(HrisCompanyLocation::class,'company_location_id')->withDefault();
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();
    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by');
    }


}
