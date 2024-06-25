<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabExamAttendanceData extends Model
{
    use SoftDeletes,Auditable,HasFactory;

    public $table = 'lab_examattendance_data';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'date',
        'enteredby',
        'class_id',
        'subject',
        'attendance',
        'lab_exam_name',
        'edited_by',
        'student_id',
        'exame_date',
        'cycle_mark',
        'model_exam_mark',
        'created_at',
        'updated_at',
        'deleted_at',
        'pass',
    ];

}
