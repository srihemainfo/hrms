<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Staffs extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staffs';
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'user_name_id',
        'shift',
        'employee_id',
        'worktype_id',
        'hybrid_working_days',
        'phone_number',
        'gender',
        'designation_id',
        'status',
        'biometric',
        'past_leave_access',
        'casual_leave',
        'sick_leave',
        'DOJ',
        'DOR',
        'edit_access',
    ];
    protected $guarded;
}
