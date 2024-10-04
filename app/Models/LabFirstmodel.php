<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabFirstmodel extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'lab_first_table';
    protected $dates = [

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'accademicYear',
        'semesterType',
        'course_id',
        'year',
        'semester',
        'modeofExam',
        'due_date',
        'subject',
        'section',
        'MarkType',
        'exam_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
