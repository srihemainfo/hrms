<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffBiometric extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_biometrics';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'staff_code',
        'in_time',
        'out_time',
        'day_punches',
        'total_hours',
        'details',
        'status',
        'employee_code',
        'employee_name',
        'shift',
        'updated_by',
        'update_status',
        'import',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
