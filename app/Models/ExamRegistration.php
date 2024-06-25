<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamRegistration extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'exam_registration';

    protected $fillable = [
        'academic_year',
        'batch',
        'course',
        'semester',
        'regulation',
        'user_name_id',
        'subject_name',
        'subject_type',
        'register_no',
        'subject_code',
        'subject_id',
        'subject_sem',
        'uploaded_date',
        'credits',
        'exam_type',
        'exam_fee',
        'exam_month',
        'exam_year',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ay()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year');
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
        return $this->belongsTo(Document::class,'user_name_id','nameofuser_id');
    }
}
