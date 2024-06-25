<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectRegistration extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'subject_registration';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'register_no',
        'student_name',
        'batch',
        'course',
        'academic_year',
        'semester',
        'section',
        'category',
        'regulation',
        'subject_code',
        'subject_name',
        'subject_id',
        'enroll_master',
        'status',
        'class_incharge',
        'dept',
        'user_name_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function enroll_masters()
    {
        return $this->belongsTo(CourseEnrollMaster::class, 'enroll_master');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function students()
    {
        return $this->belongsTo(Student::class,'user_name_id','user_name_id');
    }

    public function subjectAllotment()
    {
        return $this->belongsTo(SubjectAllotment::class,'subject_id');
    }
}
