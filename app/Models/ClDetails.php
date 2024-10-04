<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClDetails extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'cl_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_name_id',
        'name',
        'staff_code',
        'dept',
        'date',
        'cl',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
