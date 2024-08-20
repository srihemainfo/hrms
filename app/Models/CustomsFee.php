<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class CustomsFee extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'customs_fee';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'fee_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
