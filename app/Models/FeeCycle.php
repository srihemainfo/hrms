<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCycle extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'fee_cycle';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'cycle_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
