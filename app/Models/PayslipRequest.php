<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayslipRequest extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'payslip_request';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'year',
        'month',
        'status',
        'reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
