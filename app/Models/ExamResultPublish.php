<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResultPublish extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'exam_result_publish';

    protected $fillable = [
        'batch',
        'academic_year',
        'course',
        'semester',
        'regulation',
        'exam_month',
        'exam_year',
        'result_type',
        'publish_date',
        'register_no',
        'subject_1',
        'grade_1',
        'subject_2',
        'grade_2',
        'subject_3',
        'grade_3',
        'subject_4',
        'grade_4',
        'subject_5',
        'grade_5',
        'subject_6',
        'grade_6',
        'subject_7',
        'grade_7',
        'subject_8',
        'grade_8',
        'subject_9',
        'grade_9',
        'subject_10',
        'grade_10',
        'subjectcode_9',
        'subjectcode_10',
        'exam_registration_id',
        'publish',
        'user_name_id',
        'uploaded_date',
        'uploaded_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ay()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function batches()
    {
        return $this->belongsTo(Batch::class, 'batch');
    }

    public function courses()
    {
        return $this->belongsTo(ToolsCourse::class, 'course');
    }

    public function regulations()
    {
        return $this->belongsTo(ToolssyllabusYear::class, 'regulation');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_name_id', 'user_name_id');
    }

    public function personal_details()
    {
        return $this->belongsTo(PersonalDetail::class, 'user_name_id', 'user_name_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function profile()
    {
        return $this->belongsTo(Document::class, 'user_name_id', 'nameofuser_id');
    }

    public function grades_1()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_1');
    }
    public function grades_2()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_2');
    }
    public function grades_3()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_3');
    }
    public function grades_4()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_4');
    }
    public function grades_5()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_5');
    }
    public function grades_6()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_6');
    }
    public function grades_7()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_7');
    }
    public function grades_8()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_8');
    }
    public function grades_9()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_9');
    }
    public function grades_10()
    {
        return $this->belongsTo(GradeMaster::class, 'grade_10');
    }
}
