<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamTimetableCreation extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'exam_timetable_creations';


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'department',
        'course',
        'semester',
        'subject',
        'exam_name',
        'date',
        'start_time',
        'end_time',
        'block',
        'room',
        'remark',
        'accademicYear',
        'semesterType',
        'exameType',
        'year',
        'sections',
        'modeofExam',
        'co',
        'deleted_at',
        'created_at',
        'updated_at',

    ];
    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    // public function Subjects()
    // {
    //     return $this->belongsTo(Subject::class, 'name');
    // }

    // public function course()
    // {
    //     return $this->belongsTo(ToolsCourse::class, 'name');
    // }

    // public function department()
    // {
    //     return $this->belongsTo(ToolsDepartment::class, 'name');
    // }

    // public function semester()
    // {
    //     return $this->belongsTo(Semester::class, 'semester_id');
    // }
    // public function blocks()
    // {
    //     return $this->belongsTo(CollegeBlock::class, 'name');
    // }
    // public function classrooms()
    // {
    //     return $this->belongsTo(ClassRoom::class, 'name');
    // }
}
