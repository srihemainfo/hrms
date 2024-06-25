<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeBook extends Model
{
    use SoftDeletes, HasFactory, Auditable;

    public $table = 'grade_book';

    protected $fillable = [
        'user_name_id',
        'regulation',
        'batch',
        'academic_year',
        'exam_date',
        'course',
        'published_date',
        'result_type',
        'semester',
        'subject',
        'subject_code',
        'subject_title',
        'register_no',
        'grade',
        'result',
        'exam_month',
        'exam_year',
        'created_at',
        'updated_at',
        'deleted_at',
        'import'
    ];

    public function getRegulation()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function getAy()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
    }

    public function getSubject()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

    public function getCourse()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }

    public function getGrade()
    {
        return $this->belongsTo(GradeMaster::class, 'grade');
    }

    public function getStudent()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }

    public function getPersonal()
    {
        return $this->belongsTo(PersonalDetail::class, 'user_name_id', 'user_name_id');
    }

    public function getProfile()
    {
        return $this->belongsTo(Document::class, 'user_name_id', 'nameofuser_id');
    }
}
