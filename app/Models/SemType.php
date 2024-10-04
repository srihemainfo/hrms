<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SemType extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'semester_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sem_type',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
