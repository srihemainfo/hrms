<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabExamAttendance extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    protected $table = 'lab_exam_attendances';

    protected $primaryKey = 'id'; 

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lab_exam_id',
        'course',
        'date',
        'subject',
        'acyear',
        'examename',
        'year',
        'sem',
        'section',
        'total_present',
        'total_abscent',
        'att_entered',
        'date_entered',
        'entered_by',
        'mark_entereby',
        'mark_date',
        'edit_request',
        'mark_entry',
        'status',
        'cycle_exam_mark',
        'pass_count',
        'fail_count',
        'pass_percentage',
        'fail_percentage',
        'updateby',
        'publishby',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Define relationships with other models
    public function courseEnrollMaster()
    {
        return $this->belongsTo(ToolsCourse::class, 'course', 'id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'acyear', 'id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'sem', 'id');
    }

    public function teachingStaff()
    {
        return $this->belongsTo(TeachingStaff::class, 'entered_by', 'user_name_id');
    }

    public function nonTeachingStaff()
    {
        return $this->belongsTo(NonTeachingStaff::class, 'entered_by', 'user_name_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'entered_by', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id');
    }
}
