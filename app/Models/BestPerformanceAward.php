<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BestPerformanceAward extends Model
{
    use SoftDeletes, Auditable, HasFactory;


    public $table = 'bestperformance_award';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'staff_name',
        'year',
        'created_by',
        'month',
        'amount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
