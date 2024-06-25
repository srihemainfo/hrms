<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamattendanceData extends Model
{
    use SoftDeletes,Auditable,HasFactory;

    public $table = 'examattendance_data';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'batch',     // For Import Purpose
        'course',    // For Import Purpose
        'ay',        // For Import Purpose
        'semester',  // For Import Purpose
        'section',   // For Import Purpose
        'date',
        'enteredby',
        'class_id',
        'subject',
        'attendance',
        'examename',
        'edited_by',
        'student_id',
        'exame_date',
        'co_1',
        'co_2',
        'co_3',
        'co_4',
        'co_5',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
