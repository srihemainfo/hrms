<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultMaster extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'result_master';

    protected $fillable = [
        'result_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
