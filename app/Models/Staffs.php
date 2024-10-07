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

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'role_id',
        'name',
        'email',
        'phone_number',
        'gender',
        'designation_id',
        'edit_access',
        'status',
        'biometric',
        'employee_id',
        'DOJ',
        'DOR',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


}
