<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'announcement';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'announcement',
        'created_by',
        'create_date',
        'end_date',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
