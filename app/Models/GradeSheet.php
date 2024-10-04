<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeSheet extends Model
{
    use SoftDeletes, HasFactory, Auditable;

    public $table = 'grade_sheet';

    protected $fillable = [
        'regulation',
        'batch',
        'academic_year',
        'course',
        'exam_date',
        'file_path',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function getBatch()
    {
        return $this->belongsTo(Batch::class, 'batch');
    }
}
