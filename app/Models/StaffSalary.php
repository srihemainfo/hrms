<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffSalary extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_salaries';

    protected $dates = [
        'date_of_joining',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'staff_code',
        'biometric',
        'staff_name',
        'department',
        'designation',
        'date_of_joining',
        'total_working_days',
        'loss_of_pay',
        'no_of_day_payable',
        'actual_gross_salary',
        'basic_pay',
        'agp',
        'da',
        'hra',
        'arrears',
        'special_pay',
        'other_allowances',
        'appraisal_based_increment',
        'phd_allowance',
        'gross_salary',
        'it',
        'pt',
        'salary_advance',
        'other_deduction',
        'esi',
        'epf',
        'total_deductions',
        'net_salary',
        'remarks',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user_name()
    {
        return $this->belongsTo(User::class, 'user_name_id');
    }

    public function getDateOfJoiningAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfJoiningAttribute($value)
    {
        $this->attributes['date_of_joining'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
