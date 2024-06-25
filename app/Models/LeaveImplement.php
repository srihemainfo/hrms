<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveImplement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'leave_implement';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'noon',
        'staff_type',
        'leave_type',
        'reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
