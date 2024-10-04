<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffAlterationRegister extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'staff_alteration_register';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'from_date',
        'to_date',
        'staff_id',
        'alter_staffid',
        'no_of_days',
        'status',
        'dummy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
