<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendenceTable extends Model
{
    use SoftDeletes,Auditable, HasFactory;

    public $table = 'attendence_tables';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'student',
        'subject',
        'date',
        'day',
        'period',
        'attendance',
        'staff',
        'enroll_master',
        'topic',
        'unit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
